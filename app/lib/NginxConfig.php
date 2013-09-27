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
    protected $server_name;

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
    protected $config_cache = null;

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
        $filename = '';
        $filename = str_replace('*', 'any', $this->server_name);
        $filename = str_replace('.', '_', $filename);
        return $filename;
    }

    public function writeConfig()
    {
        $this->addBlankConfigLine()
                ->addConfigLine('##')
                ->addConfigLine('# Turbine Proxy Configuration File')
                ->addConfigLine('##')
                ->addBlankConfigLine()
                ->addBlankConfigLine()
                ->addConfigLine('upstream ' .$this->serverNameToFileName().'_nlb_backend {');
        foreach ($this->nlb_servers as $server) {
            $params = '';
            foreach ($server[1] as $vkey => $vval) {
                $params .= ' ' . $vkey . '=' . $vval;
            }
            $this->addConfigLine('server ' . $server[0] . $params, 1);
        }
        $this->addConfigLine('}')
                ->addBlankConfigLine()
                ->addBlankConfigLine()
                ->addConfigLine('server {')
                ->addConfigLine('listen ' . $this->listen_ports . ';', 1)
                ->addConfigLine('server_name ' . $this->server_name . ';', 1)
                ->addConfigLine('access_log /etc/turbine/logs/' . $this->serverNameToFileName() . '.access.log', 1)
                ->addConfigLine('error_log /etc/turbine/logs/' . $this->serverNameToFileName() . '.error.log', 1)
                ->addBlankConfigLine()
                ->addConfigLine('root /etc/turbine/html_pages;', 1)
                ->addConfigLine('try_files /maintenance.html @proxy;', 1)
                ->addBlankConfigLine()
                ->addConfigLine('location @proxy {', 1)
                ->addConfigLine('proxy_set_header Host \$host;', 2)
                ->addConfigLine('proxy_set_header X-Real-IP \$remote_addr;', 2)
                ->addConfigLine('proxy_set_header X-Forwarded-For \$proxy_add_x_forwarded_for;', 2)
                ->addConfigLine('proxy_pass  http://' .$this->serverNameToFileName().'_nlb_backend;', 2)
                ->addConfigLine('}', 1)
                ->addBlankConfigLine()
                ->addConfigLine('client_max_body_size 64M;', 1)
                ->addConfigLine('}');

        // Debug, we can check the output of the generated file here!
        foreach ($this->config_cache as $line) {
            echo $line;
        }
    }

}

?>
