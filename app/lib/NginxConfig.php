<?php

use Ballen\Executioner\Executer;

class NginxConfig
{

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
     * Stores a list of servers and directives for network load-balanced configuration.
     * @var array Array of servers and server config directives.
     */
    protected $nlb_servers = array();

    /**
     * Stores the generated configuration file contents as an array before being flattened and written out to the file system.
     * @var array
     */
    protected $config_cache = array();

    /**
     * Add the host header(s) of which this rule should respond too, this is the 'server_name' diirective, see: http://nginx.org/en/docs/http/server_names.html
     * @param string $hostheaders Hostname or IP address etc.
     * @return \NginxConfig
     */
    public function setHostheaders($hostheaders = '_') // '_' is the 'default' catch_all server name!
    {
        $this->server_name = $hostheaders;
        return $this;
    }

    /**
     * Sets the virtual host's port to respond/listen on.
     * @param int $port The TCP port number to listen on. (Default is 80)
     * @return \NginxConfig
     */
    public function setListenPort($port = 80)
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
        return $this;
    }

    /**
     * Removes a server from the list of Network load-balanced servers.
     * @param string $servername The name/IP of the server which you wish to remove from the config.
     * @return \NginxConfig
     */
    public function removeServerFromNLB($servername)
    {
        foreach ($this->nlb_servers as $key => $server) {
            if ($server[0] == $servername)
                unset($this->nlb_servers[$key]);
        }
        return $this;
    }

    /**
     * Resets the configuration cache array.
     * @return \NginxConfig
     */
    public function resetConfigCache()
    {
        $this->config_cache = array();
        return $this;
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
     * Adds a blank line to the configuration file (for file formatting purposes only!).
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
    public function serverNameToFileName()
    {
        $filename = explode(' ', $this->server_name);
        $filename = str_replace('*', 'any', $filename[0]);
        $filename = str_replace('.', '_', $filename);
        return $filename;
    }

    /**
     * Returns the value of a configuration item.
     */
    protected function getConfigValue($content, $setting, $end = ';')
    {
        $between = substr($content, strpos($content, $setting), strpos($content, $end) - strpos($content, $setting));
        return trim(str_replace($setting, '', $between));
    }

    /**
     * Reads and create a new config object based on an existing configuration file.
     * @param string $filename The file path and name to the configuration file.
     * @return \NginxConfig
     */
    public function readConfig($filename)
    {
        // Lets read in the existing configuration file...
        $config_contents = file_get_contents($filename);

        // Based on the current 'read in' configuration file we'll now set the server port.
        $this->setListenPort(trim($this->getConfigValue($config_contents, 'listen', '# Server host headers'), ';'));

        // Based on the current servers 'server name' (host headers) we'll set these too also!
        $this->setHostheaders(trim($this->getConfigValue($config_contents, 'server_name', '# Log files'), ';'));

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
                /**
                 * IF YOU ARE RECIEVING ERRORS HERE, CHECK THAT YOU'RE NOT TRYING TO LOAD IN CONFIG
                 * FILES FROM ANOTHER PLATFORM (EG. CONFIG FILES CREATED ON LINUX AND TRYING TO OPEN ON WINDOWS)
                 * AS THERE APPEARS TO BE AN ISSUE AT PRESENT WITH THIS, SO DELETE ALL YOUR EXISTING RULES
                 * THEN RE-CREATE THEM ON A NEW PLATFORM AND THIS DOES THE TRICK! - WILL LOOK INTO A WORK-AROUND
                 *  FOR THIS AT A LATER DATE BUT TO TBE HONEST, NOT REALLY A BIG DEAL AS THIS WOULD ONLY REALLY
                 *  HAPPEN IN DEVELOPMENT ETC.
                 */
                $opt_array[$parts[0]] = str_replace(';', '', $parts[1]);
            }
            $this->addServerToNLB(array(
                $server_parts[1], // The server address/port.
                $opt_array));
        }
        return $this;
    }

    /**
     * Writes the Nginx configuration file to the config cache ready to be sent to the browser or out to a file.
     * @return \NginxConfig
     */
    public function writeConfig()
    {
        // We now reset the config cache..
        $this->resetConfigCache();

        // Now we cache the file contents
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
                ->addConfigLine('root /etc/turbine/static;', 1)
                ->addConfigLine('try_files /maintenance.html @proxy;', 1)
                ->addBlankConfigLine()
                ->addConfigLine('# Proxy forwarding', 1)
                ->addConfigLine('location @proxy {', 1)
                ->addConfigLine('proxy_set_header Host $host;', 2)
                ->addConfigLine('proxy_set_header X-Real-IP $remote_addr;', 2)
                ->addConfigLine('proxy_set_header X-Forwarded-For $proxy_add_x_forwarded_for;', 2)
                ->addConfigLine('proxy_pass  http://' . $this->serverNameToFileName() . '_nlb_backend;', 2)
                ->addConfigLine('} # EoPFB', 1)
                ->addBlankConfigLine()
                ->addConfigLine('client_max_body_size 64M;', 1)
                ->addBlankConfigLine()
                ->addConfigLine('} #EoSB');

        return $this;
    }

    /**
     * Print the contents of the configuration file to the screen (using 'echo')
     * @return string The plaintext configuration data.
     */
    public function toSrceen()
    {
        return implode('', $this->config_cache);
    }

    /**
     * Write the contents of the configuration file to to the file system.
     * @param string $filename The file path and name of the file to save.
     * @return boolean
     */
    public function toFile($filename)
    {
        return file_put_contents($filename, implode('', $this->config_cache));
    }

    /**
     * Exports the configuration out as JSON.
     * @return string JSON representation of the configuration settings.
     */
    public function toJSON()
    {
        $serverlist = array();
        foreach ($this->nlb_servers as $value) {
            //die(var_dump($value));
            $serverlist[] = array(
                'target' => $value[0],
                'max_fails' => $value[1]['max_fails'],
                'fail_timeout' => $value[1]['fail_timeout'],
                'weight' => $value[1]['weight'],
            );
        }
        return json_encode(
                array(
                    'server_name' => $this->server_name,
                    'listen' => $this->listen_ports,
                    'nlb_servers' => $serverlist,
        ));
    }

    /**
     * Attemps to reload the nginx service.
     */
    public function reloadConfig()
    {
        $server_reload = new Executer;
        $server_reload->setApplication('service nginx reload');
        //$server_reload->execute();
    }

    /**
     * Delete the current physical configuration file.
     * @param string $filename The full path and filename to the configuration file to delete.
     * @return boolean
     */
    public function deleteConfig($filename)
    {
        return unlink($filename);
    }

}

?>
