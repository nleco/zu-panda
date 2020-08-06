<?php
/**
 * Application Configuration
 */

/**
 * Application Configuration
 *
 * Provides access to load and save configuration stored in INI files
 * 
 * $env = get_cfg_var(‘application_environment’);
 * Config::load(
 *     ROOT . ‘/Config/environment/config.master.ini’,
 *     ROOT . “/Config/environment/config.{$env}.ini”,
 *     ROOT . ‘/Config/environment/config.ini’ // if it exists.
 * );
 */
class Config
{
    /**
     * Configuration data
     *
     * @var array
     */
    public static $data;


    /**
     * Get a specific value from application configuration.
     * We use '.' to represent multiple levels of configuration.
     *
     * For example:
     *
     * <code>
     *     ; config.ini
     *
     *     [counter_works]
     *     api_url = "http://cw2.performancewarehouse.com"
     * </code>
     *
     * Can be accessed using:
     *
     * <code>
     *     echo Config::get('counter_works.api_url');
     *     // outputs string "http://cw2.performancewarehouse.com"
     * </code>
     *
     * @param string $setting configuration setting to get
     * @return mixed
     */
    public static function get($setting)
    {
        if (0 <= strpos($setting, '.')) {
            $tmp_path = explode('.', $setting);
            $data_reference_var = &self::$data;
            
            foreach ($tmp_path as $path_piece) {
                if (!empty($path_piece) && isset($data_reference_var[$path_piece])) {
                    $data_reference_var = &$data_reference_var[$path_piece];
                } else {
                    return null;
                }
            }
            
            return $data_reference_var;
        }
        
        if (isset(self::$data[$setting])) {
            return self::$data[$setting];
        } else {
            return null;
        }
    }

    public static function set($key, $value)
    {
        if (!is_string($key) || 1 !== preg_match('/^[a-z_]+\.[a-z_]+$/', $key) || strpos($key, '.') === false || strpos($key, '.') === 0) {
            return false;
        }

        $group_key = explode('.', $key);

        if (!isset(self::$data[$group_key[0]]) || !is_array(self::$data[$group_key[0]])) {
            self::$data[$group_key[0]] = array();
        }

        if (is_bool($value)) {
            $value = $value ? "1" : "0";
        } else if (is_array($value)) {
            return false;
        }
        
        self::$data[$group_key[0]][$group_key[1]] = (string)$value;
        return true;
    }

    /**
     * Load a group of config files.
     * Values are overriden if they occur in files later in the list.
     *
     * For example, here is how config files are loaded in main index.php:
     *
     * <code>
     *     $env = "<set to whatever file you named, eg: 'dev', 'production', 'test'
     *     Config::load(
     *	       ROOT . 'config.master.ini",
     *	       ROOT . "config.{$env}.ini",
     *	       ROOT . 'config.ini'
     *     );
     * </code>
     */
	public static function load()
	{
		$files = func_get_args();
		foreach ($files as $f) {
            if (file_exists($f)) {
                $data = parse_ini_file($f, true);
			    foreach($data as $group => $values) {
				    foreach($values as $k=>$v) {
					    self::$data[$group][$k] = $v;
                    }
				}
			}
		}
	}

	/**
	 * Return all configuration settings.
	 *
	 * @return mixed
	 */
	public static function all()
	{
		return self::$data;
	}
}
