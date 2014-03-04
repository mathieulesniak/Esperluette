<?php
namespace Esperluette\Model;

use Fwk\Fwk;
class Helper
{
    private static $translations;

    private static function loadI18n($language)
    {
        $path = 'Esperluette' . DIRECTORY_SEPARATOR
                . 'I18n' . DIRECTORY_SEPARATOR
                . $language . DIRECTORY_SEPARATOR
                . 'language.php';
        if (is_readable($path)) {
            include $path;
            static::$translations = $translations;
        } else {
            Fwk::Logger()->debug(sprintf('Missing translation file for %s', $language));
        }
    }


    public static function i18n()
    {
        $args = func_get_args();
        if (isset($args[0])) {
            $str    = $args[0];
            $lang   = Config::get('language', 'en_GB');

            if (static::$translations === null) {
                static::loadI18n($lang);
            }

            if (isset(static::$translations[$str])) {
                if (isset($args[1])) {
                    array_shift($args);
                    return vsprintf(static::$translations[$str], $args);
                } else {
                    return static::$translations[$str];
                }
            } else {
                //Fwk::Logger()->debug(sprintf('Missing translation in %s for \'%s\'', $lang, $str));
                return $str;
            }
        }
    }

    public static function i18nList()
    {
        $langDir    = 'Esperluette' . DIRECTORY_SEPARATOR. 'I18n';
        $langList   = array();
        $iterator   = new \DirectoryIterator($langDir);

        foreach ($iterator as $currentFile) {
            if ($currentFile->isDir() 
                && !$currentFile->isDot()
                && is_readable($currentFile->getPathname() . DIRECTORY_SEPARATOR . 'language.php')) {
                $langList[$currentFile->getFilename()] = $currentFile->getFilename();
            }
        }
        asort($langList);
        
        return $langList;
    }

    public static function listTimezones()
    {
        $abbreviations  = \DateTimeZone::listAbbreviations();
        $identifiers    = \DateTimeZone::listIdentifiers();
        $timezoneList   = array();
        $added          = array();
        $offset         = array();
        foreach ($abbreviations as $zone => $zoneData) {
            foreach ($zoneData as $currentZone) {
                if (!empty($currentZone['timezone_id']) 
                    && !in_array($currentZone['timezone_id'], $added)
                    && in_array($currentZone['timezone_id'], $identifiers)) {
                    
                    $z = new \DateTimeZone($currentZone['timezone_id']);
                    $c = new \DateTime(null, $z);
                    $currentZone['time'] = $c->format('H:i a');
                    $timezoneList[] = $currentZone;
                    $offset[] = $z->getOffset($c);
                    $added[] = $currentZone['timezone_id'];
                }
            }
        }

        array_multisort($offset, SORT_ASC, $timezoneList);
        $result = array();
        foreach ($timezoneList as $timezone) {
            $offset         = $timezone['offset'];

            $hours          = $offset / 3600;
            $remainder      = $offset % 3600;
            $sign           = $hours > 0 ? '+' : '-';
            $hour           = (int) abs($hours);
            $minutes        = (int) abs($remainder / 60);

            if ($hour == 0 && $minutes == 0) {
                $sign = ' ';
            }

            $label = 'GMT' . $sign . str_pad($hour, 2, '0', STR_PAD_LEFT) . ':' . str_pad($minutes, 2, '0');

            $result[$timezone['timezone_id']] = $timezone['timezone_id'] . ' - ' . $label . ' - ' . $timezone['time'];
        }
        
        return $result;
    }
}
