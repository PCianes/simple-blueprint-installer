#!/usr/bin/env php
<?php

class Simple_Blueprint_Installer_Generator
{
    public $dir =__DIR__;

    public function process($data)
    {
        switch ($data[1])
        {
            case 'zip':
                return $this->makeup();
                break;
            case 'class':
                return $this->makeClass($data);
                break;
            default:
                echo("Framework not understand you. ;-)");
                exit();
        }
    }

    public function makeup()
    {
        echo("Framework is packing the plugin \n");

        file_exists($this->dir.'/'.basename($this->dir).'.zip')?unlink($this->dir.'/'.basename($this->dir).'.zip'):false;
        $zip = new ZipArchive();
        $zip->open(basename($this->dir).'.zip', ZipArchive::CREATE);

        $dirName =$this->dir;

        if (!is_dir($dirName)) {
            throw new Exception('Directory ' . $dirName . ' does not exist');
        }

        $dirName = realpath($dirName);
        if (substr($dirName, -1) != '/') {
            $dirName.= '/';
        }

        /*
        * NOTE BY danbrown AT php DOT net: A good method of making
        * portable code in this case would be usage of the PHP constant
        * DIRECTORY_SEPARATOR in place of the '/' (forward slash) above.
        */

        $dirStack = array($dirName);
        //Find the index where the last dir starts
        $cutFrom = strrpos(substr($dirName, 0, -1), '/')+strlen($this->dir)+1;

        while (!empty($dirStack)) {
            $currentDir = array_pop($dirStack);
            $filesToAdd = array();

            $dir = dir($currentDir);

            while (false !== ($node = $dir->read())) {
                if ( ($node == '.vscode') || ($node == 'tests') || ($node == 'node_modules') || ($node == 'vendor') || ($node == 'tests') ||($node == 'phpunit.xml') ||($node == 'gruntfile.js') ||($node == 'gulpfile.js') ||($node == 'package.json') || ($node == '..') || ($node == '.') || ($node=='.git')  || ($node=='composer.json') || ($node=='composer.lock') || ($node=='.gitignore') || ($node =='simple-blueprint-installer-generator') || ($node=='readme.md') || ($node=='bitbucket-pipelines.yml') || ($node=='.gitmodules') || ($node=='.editorconfig') || ($node=='.jshintrc') || ($node=='make') ) {
                    continue;
                }
                if (is_dir($currentDir . $node)) {
                    array_push($dirStack, $currentDir . $node . '/');
                }
                if (is_file($currentDir . $node)) {
                    $filesToAdd[] = $node;
                }
            }

            $localDir = substr($currentDir, $cutFrom);

            $zip->addEmptyDir($localDir);
            foreach ($filesToAdd as $file) {
                $zip->addFile($currentDir . $file, $localDir . $file);
                echo("Added $localDir$file into plugin  \n");
            }
        }

        $zip->close();
        echo("The plugin's zip file is OK!");
    }


    public function makeClass($data)
    {
    $plugin_name = '$plugin_name';
    $version = '$version';
    $this1 = '$this->plugin_name = $plugin_name';
    $this2 = '$this->version = $version';
    $input ="<?php

/**
 * The info about this file
 *
 * @link       https://pablocianes.com/
 * @since      1.0.0
 *
 * @package    Simple_Blueprint_Installer
 * @subpackage Simple_Blueprint_Installer/includes
 */

/**
 * The info about this class
 *
 * Defines the functions of this class
 *
 * @package    Simple_Blueprint_Installer
 * @subpackage Simple_Blueprint_Installer/includes
 * @author     Pablo Cianes <pablo@pablocianes.com>
 */
class Simple_Blueprint_Installer_$data[2] {

    /**
     * The ID of this plugin.
     *
     * @since    1.0.0
     * @access   private
     * @var      string    $plugin_name    The ID of this plugin.
     */
    private $plugin_name;

    /**
     * The version of this plugin.
     *
     * @since    1.0.0
     * @access   private
     * @var      string    $version    The current version of this plugin.
     */
    private $version;

    /**
     * Initialize the class and set its properties.
     *
     * @since    1.0.0
     * @param      string    $plugin_name       The name of this plugin.
     * @param      string    $version    The version of this plugin.
     */
    public function __construct( $plugin_name, $version ) {

        $this1;
        $this2;
    }

    /**
     * Description of the static function
     *
     * @since    1.0.0
     */
    public static function name_of_the_function() {


    }


}";

        file_put_contents(__DIR__."/$data[3]/class-simple-blueprint-installer-$data[2].php", $input);
        exit("File class-simple-blueprint-installer-$data[2].php created into $data[3] folder");
    }

}

$plugin_name_generator = new Simple_Blueprint_Installer_Generator();

exit( $plugin_name_generator->process( $argv ) );



