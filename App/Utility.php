<?php

namespace App;

class Utility {
    /**
         * Replace arrow with locale specific word
         *
         * @param string $string
         *
         * @return string
         */
        public static function replaceArrowWithWord($string) {
            return str_replace('→', self::joinWord(), $string);
        }

        /**
         * Get locale specific word for "to"
         * Used in route url slug
         *
         * @return string
         */
        private static function joinWord(): string {
            $locale   = get_bloginfo("language");
            $language = explode("-", $locale)[0];
            switch ($language) {
                case "es":
                    return "a";
                case "de":
                    return "zu";
                case "fr":
                    return "à";
                case "it":
                    return "a";
                case "pt":
                    return "para";
                case "ru":
                    return "к";
                case "ja":
                    return "に";
                case "ko":
                    return "에";
                case "zh":
                    return "到";
                case "ar":
                    return "إلى";
                case "hi":
                    return "को";
                case "bn":
                    return "পর্যন্ত";
                case "pa":
                    return "ਲਈ";
                case "te":
                    return "కు";
                case "mr":
                    return "करिता";
                case "ta":
                    return "க்கு";
                case "gu":
                    return "માટે";
                case "kn":
                    return "ಗೆ";
                case "ml":
                    return "വരെ";
                case "th":
                    return "ถึง";
                case "vi":
                    return "đến";
                case "tr":
                    return "için";
                case "nl":
                    return "naar";
                case "pl":
                    return "do";
                case "sv":
                    return "till";
                case "da":
                    return "til";
                case "fi":
                    return "saakka";
                case "no":
                    return "til";
                case "cs":
                    return "na";
                case "sk":
                    return "na";
                case "hu":
                    return "ig";
                case "el":
                    return "προς";
                case "bg":
                    return "към";
                case "ro":
                    return "către";
                case "uk":
                    return "до";
                case "hr":
                    return "do";
                case "sr":
                    return "ka";
                case "sl":
                    return "do";
                case "lt":
                    return "iki";
                case "lv":
                    return "uz";
                case "et":
                    return "kuni";
                case "en":
                default:
                    return "to";
            }
        }
}
