<?php

namespace Outpost\Environments;

class EnvironmentTest extends \PHPUnit_Framework_TestCase {

  protected $errorLevel;

  public function setUp() {
    $this->errorLevel = error_reporting();
  }

  public function tearDown() {
    error_reporting($this->errorLevel);
  }

  public function testGetRootDirectory() {
    $path = realpath(sys_get_temp_dir());
    $env = new Environment($path);
    $this->assertEquals($path, $env->getRootDirectory());
  }

  public function testGetAssetCacheDirectoryAndCreateDirectory() {
    $path = realpath(sys_get_temp_dir());
    $assetsCacheDir = "$path/cache/assets";
    $env = new Environment($path);
    $this->assertEquals($assetsCacheDir, $env->getAssetCacheDirectory());
    $this->assertTrue(is_dir($assetsCacheDir));
    rmdir($assetsCacheDir);
    rmdir(dirname($assetsCacheDir));
  }

  public function testGetAssetCacheDirectoryWithoutCreatingDirectory() {
    $path = realpath(sys_get_temp_dir());
    $assetsCacheDir = "$path/cache/assets";
    $env = new Environment($path);
    $this->assertEquals($assetsCacheDir, $env->getAssetCacheDirectory(false));
    $this->assertFalse(is_dir($assetsCacheDir));
  }

  public function testGetGeneratedAssetsDirectoryAndCreateDirectory() {
    $path = realpath(sys_get_temp_dir());
    $assetsDir = "$path/docroot/_assets";
    $env = new Environment($path);
    $this->assertEquals($assetsDir, $env->getGeneratedAssetsDirectory());
    $this->assertTrue(is_dir($assetsDir));
    rmdir($assetsDir);
    rmdir(dirname($assetsDir));
  }

  public function testGetGeneratedAssetsDirectoryWithoutCreatingDirectory() {
    $path = realpath(sys_get_temp_dir());
    $assetsDir = "$path/docroot/_assets";
    $env = new Environment($path);
    $this->assertEquals($assetsDir, $env->getGeneratedAssetsDirectory(false));
    $this->assertFalse(is_dir($assetsDir));
  }

  public function testGetPublicDirectoryAndCreateDirectory() {
    $path = realpath(sys_get_temp_dir());
    $publicDir = "$path/docroot";
    $env = new Environment($path);
    $this->assertEquals($publicDir, $env->getPublicDirectory());
    $this->assertTrue(is_dir($publicDir));
    rmdir($publicDir);
  }

  public function testGetPublicDirectoryWithoutCreatingDirectory() {
    $path = realpath(sys_get_temp_dir());
    $publicDir = "$path/docroot";
    $env = new Environment($path);
    $this->assertEquals($publicDir, $env->getPublicDirectory(false));
    $this->assertFalse(is_dir($publicDir));
  }

  public function testGetSettings() {
    $settings = ['one' => 'two'];
    $root = sys_get_temp_dir();
    $settingsPath = "$root/settings.json";
    file_put_contents($settingsPath, json_encode($settings));
    $env = new Environment($root);
    $this->assertEquals($settings, $env->getSettings());
    unlink($settingsPath);
  }

  public function testGetSetting() {
    $settings = ['one' => 'two'];
    $root = sys_get_temp_dir();
    $settingsPath = "$root/settings.json";
    file_put_contents($settingsPath, json_encode($settings));
    $env = new Environment($root);
    $this->assertEquals($settings['one'], $env->getSetting('one'));
    unlink($settingsPath);
  }

  public function testHasSetting() {
    $settings = ['one' => 'two'];
    $root = sys_get_temp_dir();
    $settingsPath = "$root/settings.json";
    file_put_contents($settingsPath, json_encode($settings));
    $env = new Environment($root);
    $this->assertTrue($env->hasSetting('one'));
    $this->assertFalse($env->hasSetting('two'));
    unlink($settingsPath);
  }

  public function testGetSecrets() {
    $secrets = ['one' => 'two'];
    $root = sys_get_temp_dir();
    $secretsPath = "$root/secrets.json";
    file_put_contents($secretsPath, json_encode($secrets));
    $env = new Environment($root);
    $this->assertEquals($secrets, $env->getSecrets());
    unlink($secretsPath);
  }

  public function testGetSecret() {
    $secrets = ['one' => 'two'];
    $root = sys_get_temp_dir();
    $secretsPath = "$root/secrets.json";
    file_put_contents($secretsPath, json_encode($secrets));
    $env = new Environment($root);
    $this->assertEquals($secrets['one'], $env->getSecret('one'));
    unlink($secretsPath);
  }

  public function testHasSecret() {
    $secrets = ['one' => 'two'];
    $root = sys_get_temp_dir();
    $secretsPath = "$root/secrets.json";
    file_put_contents($secretsPath, json_encode($secrets));
    $env = new Environment($root);
    $this->assertTrue($env->hasSecret('one'));
    $this->assertFalse($env->hasSecret('two'));
    unlink($secretsPath);
  }
}