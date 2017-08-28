<?php
namespace Grohiro\Crontab;

use PHPUnit\Framework\TestCase;

/**
 * Test for Crontab
 */
class CrontabTest extends TestCase
{
    /**
     * 初期化テスト
     */
    public function testConstructor()
    {
        $crontab = new Crontab();
        $this->assertEquals("", $crontab->__toString());
    }

    /**
     * system('crontab -l')の内容で初期化する
     * crontabの内容で初期化して__toString()の結果が同じになることを確認する
     */
    public function testInit()
    {
        $data = [
            "",
            "*/5 * * * * /usr/bin/ntpdate",
            "*/5 * * * * /usr/bin/ntpdate\n0 4 * * * ls -l /var/log",
        ];
        $crontab = new Crontab();

        foreach ($data as $entry) {
            $crontab->init($entry);
            $this->assertEquals($entry, $crontab->__toString());
        }
    }

    /**
     * 新しいエントリを追加する
     */
    public function testAddNewEntry() {
        $crontab = new Crontab();
        $crontab->init('MAILTO=crontab@example.com');

        $crontab->addNewEntry(1, "0 4 * * * ls -l /var/log");
        $this->assertEquals("MAILTO=crontab@example.com\n0 4 * * * ls -l /var/log #phpcrontab:1", $crontab->__toString());

        $crontab->addNewEntry(2, "0 5 * * * ls -l /var/spool/mail");
        $this->assertEquals("MAILTO=crontab@example.com\n0 4 * * * ls -l /var/log #phpcrontab:1\n0 5 * * * ls -l /var/spool/mail #phpcrontab:2", $crontab->__toString());
    }

    public function testUpdateEntry() {
        $crontab = new Crontab();
        $crontab->init('MAILTO=crontab@example.com');

        $crontab->addNewEntry(1, "0 4 * * * ls -l /var/log");
        $this->assertEquals("MAILTO=crontab@example.com\n0 4 * * * ls -l /var/log #phpcrontab:1", $crontab->__toString());
        $replaced = $crontab->updateEntry(1, "30 5 * * * ls -l /var/log");
        $this->assertTrue($replaced);
        $this->assertEquals("MAILTO=crontab@example.com\n30 5 * * * ls -l /var/log #phpcrontab:1", $crontab->__toString());
    }

    /**
     * 
     */
    public function testUpdateOrCreateEntry()
    {
        $crontab = new Crontab();
        $crontab->addNewEntry(1, "0 4 * * * ls -l /var/log");
        $replaced = $crontab->updateEntry(2, "30 5 * * * ls -l /var/spool/mail");
        $this->assertFalse($replaced);
        $this->assertEquals("0 4 * * * ls -l /var/log #phpcrontab:1\n30 5 * * * ls -l /var/spool/mail #phpcrontab:2", $crontab->__toString());
        
    }

    /**
     * Test removeEntry()
     */
    public function testRemoveEntry() {
        $crontab = new Crontab();
        $crontab->addNewEntry(1, "0 4 * * * ls -l /var/log");
        $removed = $crontab->removeEntry(1);
        $this->assertTrue($removed);
        $this->assertEquals("", $crontab->__toString());
    }
}
