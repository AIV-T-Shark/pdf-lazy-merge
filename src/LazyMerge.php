<?php


namespace Giahao9899\PdfLazyMerge;


class LazyMerge
{
    public function merge($content) {
        $lines = explode("\n", $content);
        $new_lines = [];
        
        foreach ($lines as $k => $line) {
            if (empty($lines[$k+1])) {
                $new_lines[] = $line;
                continue;
            }
            
            $next_line = $lines[$k+1];
            $word_line = $this->extractWords($line);
            $word_next_line = $this->extractWords($next_line);
            if (empty($word_line) || empty($word_next_line))  {
                $new_lines[] = $line;
                continue;
            }
            
            if (ctype_upper($word_next_line[0][0]))  {
                $new_lines[] = $line;
                continue;
            }
            $string = end($word_line) . ' ' . $word_next_line[0];
            if ($this->search($string, $lines)) {
                $new_lines[] = $line . ' ' . $next_line;
            } else {
                $new_lines[] = $line;
            }
        }
        
        $content = implode("\n", $new_lines);
        return $content;
    }
    
    public function search($string, array $lines) {
        foreach ($lines as $line) {
            if (preg_match("/$string/ui", $line))
                return true;
        }
        return false;
    }
    
    public function extractWords(string $string) {
        $string = preg_replace( "/[\(\[].*[\)\]]/", " ", $string);
        $string = preg_replace( "/[\W\p{Z}\p{N}]/u", " ", $string);
        $string = preg_replace( "/\s{2,}/", " ", $string);
        
        $latin = $cjk = $hangul = [];
        if(preg_match_all("/[a-zàâçéèêëîïôûùüÿñæœ]{2,}/ui", $string, $matches)){
            $latin = $matches[0];
        }
        if(preg_match_all("/[\p{Hiragana}\p{Katakana}\p{Han}]/ui", $string, $matches)){
            $cjk = $matches[0];
        }
        if(preg_match_all("/[\p{Hangul}]/ui", $string, $matches)){
            $hangul = $matches[0];
        }
        
        return [...$latin, ...$cjk, ...$hangul];
    }
}