<?php
namespace Grohiro\Crontab;

/**
 * php-crontab
 *
 */
class Crontab
{
    /**
     * @var array
     */
    private $crontab;

    public function __construct()
    {
        $this->crontab = [];
    }

    /**
     * Read crontab entries
     *
     * @param string $crontab
     * @return Crontab $this
     */
    public function init($crontab)
    {
        $this->crontab = explode("\n", $crontab);
        return $this;
    }

    /**
     * Add the new crontab entry
     */
    public function addNewEntry($key, $entry)
    {
        $this->crontab[] = self::entryWithKey($key, $entry);
    }

    /**
     * Replace the entry found by `$key`.
     */
    public function updateEntry($key, $newEntry)
    {
        $replaced = false;

        $regex = preg_quote(self::key($key));
        foreach ($this->crontab as $index => $entry) {
            if (preg_match("/$regex$/", $entry)) {
                $entryWithKey = self::entryWithKey($key, $newEntry);
                $this->crontab[$index] = $entryWithKey;
                $replaced = true;
            }
        }

        return $replaced;
    }

    /**
     *
     */
    public function entryWithKey($key, $entry)
    {
        return self::joinKey(self::key($key), $entry);
    }

    public static function joinKey($key, $entry)
    {
        return $entry . " " . $key;
    }

    /**
     * Alias for `crontab -l`
     */
    public static function l()
    {
        $output = "";
        exec('crontab -l', $output);
        return implode("\n", $output);
    }

    /**
     *
     */
    public function save()
    {
        $out = null;
        try {
            $crontab = $this->__toString();
            $out = popen('cat - | crontab', 'w');
            for ($written = 0; $written < strlen($crontab); $written += $fwrite) {
                $fwrite = fwrite($out, substr($crontab, $written));
                if ($fwrite === false) {
                    break;
                }
                echo ".";
            }
            fwrite($out, "");
            pclose($out);
            return true;
        } catch (\Exception $ex) {
            if ($out) {
                pclose($out);
            }
            throw new \Exception("Failed to save crontab:" . $ex->getMessage());
        }
    }

    /**
     * Generate an entry identifier
     *
     * @param string $key An entry key
     * @return string An identifier
     */
    public static function key($key)
    {
        return "#phpcrontab:" . $key;
    }

    /**
     * Returns string representation
     */
    public function __toString()
    {
        return implode("\n", $this->crontab);
    }
}