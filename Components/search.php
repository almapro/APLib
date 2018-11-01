<?php
    namespace APLib;

    /**
     * Search - A class to search the given commands
     */
    class Search
    {

        /**
		 * @var  array  $commands  an array containing search commands
		 */
		private static $commands  =  array();

        /**
		 * @var  array  $phrases  an array containing phrases
		 */
		private static $phrases  =  array();

        /**
		 * @var  array  $placeholders  an array containing placeholders
		 */
		private static $placeholders  =  array();

        /**
         *  Add a command to the list of commands
         *
         * @param  string $name     command's name
         * @param  mixed  $response command's response
         *
         * @return void
         */
        public static function addCommand($name, $response)
        {
            static::$commands[$name] = $response;
        }

        /**
         * Executes a command and returns the result
         *
         * @param  string $name command to execute
         * @param  mixed  $args command's arguments [Default: null]
         *
         * @return mixed
         */
        public static function command($name, $args = null)
        {
            if(!isset(static::$commands[$name])) return null;
            if(is_callable(static::$commands[$name])) return static::$commands[$name]($args);
            else return static::$commands[$name];
        }

        /**
         * Returns all defined commands
         *
         * @return array
         */
        public static function commands()
        {
            $cmds = array();
            foreach (static::$commands as $command => $ignored)
            {
                $cmds[] = $command;
            }
            return $cmds;
        }

        /**
         *  Add a placeholder to the list of placeholders
         *
         * @param  string $placeholder a placeholder to add
         * @param  mixed  $values      a value or values to replace the placeholder with
         *
         * @return void
         */
        public static function addPlaceholder($placeholder, $values)
        {
            static::$placeholders[$placeholder] = $values;
        }

        /**
         *  Parses any placeholders found in the given phrase
         *
         * @param  string $phrase a placeholder to add
         *
         * @return array
         */
        public static function parse($phrase)
        {
            $phrases = array($phrase);
            foreach (static::$placeholders as $placeholder => $values)
            {
                $removePhrases = array();
                $parsedPhrases = array();
                for ($i=0; $i < sizeof($phrases); $i++) {
                    if($placeholder == '') continue;
                    if(strpos($phrases[$i], $placeholder) !== false)
                    {
                        if(is_array($values))
                        {
                            foreach ($values as $value)
                            {
                                $parsedPhrases[] = str_replace($placeholder, $value, $phrases[$i]);
                            }
                            $removePhrases[] = $phrases[$i];
                        } else {
                            $parsedPhrases[] = str_replace($placeholder, $values, $phrases[$i]);
                        }
                    }
                }
                $phrases = array_diff($phrases, $removePhrases);
                $phrases = array_merge($phrases, $parsedPhrases);
            }
            return $phrases;
        }

        /**
         *  Add a phrase to the list of phrases
         *
         * @param  string $phrase a phrase to add
         *
         * @return void
         */
        public static function addPhrase($phrase)
        {
            static::$phrases[] = $phrase;
        }

        /**
         * Match some words and return all matching phrases
         *
         * @param  array $words words to match
         * @param  bool  $all   whether or not to match all words [Default: true]
         *
         * @return array
         */
        public static function match($words, $all = true)
        {
            $phrases = array();
            foreach (static::$phrases as $phrase)
            {
                $newPhrases = array();
                $c_phrases  = static::parse($phrase);
                foreach ($c_phrases as $c_phrase)
                {
                    $matched = true;
                    foreach ($words as $word)
                    {
                        if($word == '') continue;
                        if(strpos(strtolower($c_phrase), strtolower($word)) === false)
                        {
                            $matched = false;
                            if($all) break;
                        }else{
                            if(!$all){
                                $matched = true;
                                break;
                            }
                        }
                    }
                    if(!$matched) continue;
                    $c_phrase_parts = explode(' ', $c_phrase);
                    $f_phrase       = '';
                    foreach ($c_phrase_parts as $c_phrase_word)
                    {
                        $c_p_word = $c_phrase_word;
                        foreach ($words as $word)
                        {
                            if($word == '') continue;
                            $pos = strpos(strtolower($c_p_word), strtolower($word));
                            if($pos !== false)
                            {
                                $c_p_word = substr_replace($c_p_word, '<b>'.$word.'</b>', $pos, strlen($word));
                                break;
                            }
                        }
                        $f_phrase .= $c_p_word.' ';
                    }
                    $f_phrase     = trim($f_phrase);
                    $newPhrases[] = $f_phrase;
                }
                $phrases = array_diff($phrases, $newPhrases);
                $phrases = array_merge($phrases, $newPhrases);
            }
            return $phrases;
        }

        /**
         * Returns all phrases
         *
         * @return array
         */
        public static function phrases()
        {
            return static::$phrases;
        }
    }
?>
