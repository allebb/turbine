<?php

//namespace Turbine;

class NginxConfig
{

    /**
     * Sets the nginx 'include' configuration file path/name of which to write the config chagnes too.
     * @var string The file path and name to the Nginx configuration file to write too.
     */
    protected $config_file = null;

    /**
     * The server host header(s) to listen for.
     * @var string
     */
    protected $server_name = null;

    /**
     * The list of server ports to respond on (default is 80)
     * @var type
     */
    protected $listen_ports = '80';

    /**
     * Stores a list of servers for network load-balanced configurations.
     * @var array Array of servers and server config parameters.
     */
    protected $nlb_servers = array();

    /**
     * Stores the generated configuration file contents as an array before being flattened and written out.
     * @var type
     */
    protected $config_cache = array();

    /**
     * Add the host header(s) of which this rule should respond too.
     * @param string $hostheaders
     * @return \NginxConfig
     */
    public function setHostheaders($hostheaders = '_default_')
    {
        $this->server_name = $hostheaders;
        return $this;
    }

    public function setListenPort($port)
    {
        $this->listen_ports = $port;
        return $this;
    }

    /**
     * Adds a server to the list configuration for the NLB.
     * @param array $config_array Format:
     *   array('172.25.62.11', array(
     * 'weight' => 1
     * 'max_fails' => 1
     * 'fail_timeout' => 30))
     */
    public function addServerToNLB($config_array)
    {
        $this->nlb_servers[] = $config_array;
    }

    /**
     * Adds a configuration line to the config cache.
     * @param string $line The line contents to add.
     * @param int $tabs The number of tabs (4 white spaces) to add to the line (default is 0)
     * @return \Turbine\NginxConfig
     */
    protected function addConfigLine($line, $tabs = 0)
    {
        $this->config_cache[] = str_repeat(' ', ($tabs * 4)) . $line . PHP_EOL;
        return $this;
    }

    public function resetConfigCache()
    {
        $this->config_cache = array();
    }

    /**
     * Adds a simple blank line to the configuration file.
     * @return \Turbine\NginxConfig
     */
    protected function addBlankConfigLine()
    {
        $this->addConfigLine('');
        return $this;
    }

    /**
     * Formats a host name to enable it to be used as  a file name (used for config, log and access files.)
     * @return type
     */
    protected function serverNameToFileName()
    {
        $filename = explode(' ', $this->server_name);
        $filename = str_replace('*', 'any', $filename[0]);
        $filename = str_replace('.', '_', $filename);
        return $filename;
    }

    protected function getConfigValue($content, $setting, $end = ';')
    {
        $between = substr($content, strpos($content, $setting), strpos($content, $end) - strpos($content, $setting));
        return trim(str_replace($setting, '', $between));
    }

    public function readConfig($filename)
    {
        // Lets read in the existing configuration file...
        $config_contents = file_get_contents($filename);
        //die($config_contents);

        // We now reset the config cache..
        $this->resetConfigCache();

        // Based on the current 'read in' configuration file we'll now set the server port.
        $this->setListenPort($this->getConfigValue($config_contents, 'listen'));

        // Based on the current servers 'server name' (host headers) we'll set these too also!
        $this->setHostheaders($this->getConfigValue($config_contents, 'server_name', ' '));
        // Here we set the list of NLB servers including their additonal configs.
        $servers = $this->getConfigValue($config_contents, '_backend {', '} #EoLB');
        $list_of_servers = explode(PHP_EOL, $servers);
        foreach ($list_of_servers as $server) {
            $server = trim($server); // We clear up the excess whitespacing.
            // Now we split up the values into an array ready to put back into our object...
            $server_parts = explode(' ', $server);
            // We'll now reset the array to only include the other user provided options...
            $options = array_slice($server_parts, 2);
            $opt_array = array();
            foreach ($options as $option) {
                $parts = explode('=', $option);
                $opt_array[$parts[0]] = str_replace(';', '', $parts[1]);
            }
            $this->addServerToNLB(array(
                $server_parts[1], // The server address/port.
                $opt_array));
        }

        // Done!
    }

    /**
     * Writes the Nginx configuration out to the configuration file.
     */
    public function writeConfig()
    {
        $this->addConfigLine('##')
                ->addConfigLine('# Turbine Proxy Configuration File for ' . $this->server_name)
                ->addConfigLine('##')
                ->addBlankConfigLine()
                ->addConfigLine('###########################################################################')
                ->addConfigLine('# !!! DO NOT MANUALLY EDIT THIS FILE, THIS FILE IS MANAGED BY TURBINE !!! #')
                ->addConfigLine('###########################################################################')
                ->addBlankConfigLine()
                ->addBlankConfigLine()
                ->addConfigLine('# Load-balancer configuration')
                ->addConfigLine('upstream ' . $this->serverNameToFileName() . '_nlb_backend {');
        foreach ($this->nlb_servers as $server) {
            $params = '';
            foreach ($server[1] as $vkey => $vval) {
                $params .= ' ' . $vkey . '=' . $vval;
            }
            $this->addConfigLine('server ' . $server[0] . $params . ';', 1);
        }
        $this->addConfigLine('} #EoLB')
                ->addBlankConfigLine()
                ->addConfigLine('# Server block configuration')
                ->addConfigLine('server {')
                ->addBlankConfigLine()
                ->addConfigLine('# Listening port', 1)
                ->addConfigLine('listen ' . $this->listen_ports . ';', 1)
                ->addBlankConfigLine()
                ->addConfigLine('# Server host headers', 1)
                ->addConfigLine('server_name ' . $this->server_name . ';', 1)
                ->addBlankConfigLine()
                ->addConfigLine('# Log files', 1)
                ->addConfigLine('access_log /etc/turbine/logs/' . $this->serverNameToFileName() . '.access.log;', 1)
                ->addConfigLine('error_log /etc/turbine/logs/' . $this->serverNameToFileName() . '.error.log;', 1)
                ->addBlankConfigLine()
                ->addConfigLine('root /etc/turbine/html_pages;', 1)
                ->addConfigLine('try_files /maintenance.html @proxy;', 1)
                ->addBlankConfigLine()
                ->addConfigLine('# Proxy forwarding', 1)
                ->addConfigLine('location @proxy {', 1)
                ->addConfigLine('proxy_set_header Host $host;', 2)
                ->addConfigLine('proxy_set_header X-Real-IP $remote_addr;', 2)
                ->addConfigLine('proxy_set_header X-Forwarded-For $proxy_add_x_forwarded_for;', 2)
                ->addConfigLine('proxy_pass  http://' . $this->serverNameToFileName() . '_nlb_backend;', 2)
                ->addConfigLine('}', 1)
                ->addBlankConfigLine()
                ->addConfigLine('client_max_body_size 64M;', 1)
                ->addConfigLine('} #EoSB');


        // Debug, we can check the output of the generated file here!
        foreach ($this->config_cache as $line) {
            echo $line;
        }
        // This will need to be written out to a file in /etc/turbine/rcps/(filename)
    }

}

?>
