<?php
use Emgag\Flysystem\Hash\HashPlugin;
use League\Flysystem\Adapter\Local;
use League\Flysystem\FileNotFoundException;
use League\Flysystem\Filesystem;
use PHPUnit\Framework\TestCase;

/**
 * Test class for hash plugin
 */
class HashPluginTest extends TestCase
{

    /**
     * @var $fs Filesystem
     */
    protected $fs;

    /**
     * @var $plugin HashPlugin
     */
    protected $plugin;

    /**
     * Setup filesystem object
     *
     */
    protected function setUp(): void
    {
        $this->fs     = new Filesystem(new Local(__DIR__ . '/files'));
        $this->plugin = new HashPlugin;
        $this->fs->addPlugin($this->plugin);

        // make sure test file3.txt is not readable
        chmod(__DIR__ . '/files/file3.txt', 0);
    }

    /**
     * Test plugin methods directly
     */
    public function testPlugin()
    {
        self::assertEquals('hash', $this->plugin->getMethod());
    }

    /**
     * Check correct hash values
     *
     * @param $file
     * @param $algo
     * @param $expect
     * @dataProvider providerHashes
     */
    public function testHash($file, $algo, $expect)
    {
        if (function_exists('hash_equals')) {
            self::assertTrue(hash_equals($expect, $this->fs->hash($file, $algo)));
        } else {
            self::assertEquals($expect, $this->fs->hash($file, $algo));
        }
    }

    /**
     * Check if default hash algo is sha256 as promised
     */
    public function testDefaultHash()
    {
        self::assertEquals(
            $this->fs->hash('file1.txt'),
            $this->fs->hash('file1.txt', 'sha256')
        );
    }

    public function testUnsupportedAlgo()
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->fs->hash('file1.txt', 'supersecretnsaalgorithm');
    }

    public function testFileNotFound()
    {
        $this->expectException(FileNotFoundException::class);
        $this->fs->hash('filenotfound');
    }

    /**
     * Test unreadable file
     */
    public function testUnreadableFile()
    {
        self::assertFalse(@$this->fs->hash('file3.txt'));
    }

    /**
     * @return array
     */
    public function providerHashes()
    {
        return [
            ['file1.txt', 'sha256', 'c147efcfc2d7ea666a9e4f5187b115c90903f0fc896a56df9a6ef5d8f3fc9f31'],
            ['file2.txt', 'sha256', '3377870dfeaaa7adf79a374d2702a3fdb13e5e5ea0dd8aa95a802ad39044a92f'],
            ['file1.txt', 'sha1', '60b27f004e454aca81b0480209cce5081ec52390'],
            ['file2.txt', 'sha1', 'cb99b709a1978bd205ab9dfd4c5aaa1fc91c7523'],
            ['file1.txt', 'md5', '826e8142e6baabe8af779f5f490cf5f5'],
            ['file2.txt', 'md5', '1c1c96fd2cf8330db0bfa936ce82f3b9']
        ];
    }


}
