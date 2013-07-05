<?php

namespace Kraken\Managers\Services;

use Kraken\Factories\DataFactory;
use Symfony\Bridge\Monolog\Logger;
use Kraken\Exceptions\ServiceDisableException;
use Kraken\Exceptions\ServiceNoKeyException;
use Kraken\Exceptions\ServiceOutOfOrderException;

/**
 * translate service USING MYMEMORY FREE SERVICE API
 * Class WebCrawlerService
 * @package Kraken\Managers\Services
 */
class TranslateService extends BaseService {

    /**
     * The service webcrawler
     * @var WebCrawlerService
     */
    protected $webcrawlerService;

    const AFRIKAANS = "af";
    const ALBANIAN = "sq";
    const ARABIC = "ar";
    const AZERBAIJANI = "az";
    const BASQUE = "eu";
    const BENGALI = "bn";
    const BELARUSIAN = "be";
    const BULGARIAN = "bg";
    const CATALAN = "ca";
    const CHINESE_SIMPLIFIED = "zh-CN";
    const CHINESE_TRADITIONAL = "zh-TW";
    const CROATIAN = "hr";
    const CZECH = "cs";
    const DANISH = "da";
    const DUTCH = "nl";
    const ENGLISH = "en";
    const ESPERANTO = "eo";
    const ESTONIAN = "et";
    const FILIPINO = "tl";
    const FINNISH = "fi";
    const FRENCH = "fr";
    const GALICIAN = "gl";
    const GEORGIAN = "ka";
    const GERMAN = "ge";
    const GREEK = "el";
    const GUJARATI = "gu";
    const HAITIAN_CREOLE = "ht";
    const HEBREW = "iw";
    const HINDI = "hi";
    const HUNGARIAN = "hu";
    const ICELANDIC = "is";
    const INDONESIAN = "id";
    const IRISH = "ga";
    const ITALIAN = "it";
    const JAPANESE = "ja";
    const KANNADA = "kn";
    const KOREAN = "ko";
    const LATIN = "la";
    const LATVIAN = "lv";
    const LITHUANIAN = "lt";
    const MACEDONIAN = "mk";
    const MALAY = "ms";
    const MALTESE = "mt";
    const NORWEGIAN = "no";
    const PERSIAN = "fa";
    const POLISH = "pl";
    const PORTUGUESE = "pt";
    const ROMANIAN = "ro";
    const RUSSIAN = "ru";
    const SERBIAN = "sr";
    const SLOVAK = "sk";
    const SLOVENIAN = "sl";
    const SPANISH = "es";
    const SWAHILI = "sw";
    const SWEDISH = "sv";
    const TAMIL = "ta";
    const TELUGU = "te";
    const THAI = "th";
    const TURKISH = "tr";
    const UKRAINIAN = "uk";
    const URDU = "ur";
    const VIETAMESE = "vi";
    const WELSH = "cy";
    const YIDDISH = "yi";

    public function __construct(Logger $logger,$enable,$webcrawlerService){

        $this->logger=$logger;
        $this->enable = $enable;
        $this->webcrawlerService = $webcrawlerService;
    }

    /**
     * Translate content into another language
     * @param $data Data
     * @param $input_lang String input language
     * @param $output_lang String output language
     * @param $keepOriginal boolean keep original content too
     * @return same content translated
     */
    public function translate($data,$input_lang,$output_lang,$keepOriginal=false)
    {
        try{
            if(!$this->isAvailable()) throw new ServiceDisableException();

            if(DataFactory::getInstance()->isAnInstance($data,DataFactory::TYPE_LIST_ARTICLE))
            {
                foreach($data->getContent() as $article)
                {
                    $trans_title = $this->request($article->getTitle(),$input_lang,$output_lang);
                    $trans_content = $this->request($article->getContent(),$input_lang,$output_lang);
                    if($keepOriginal)
                    {
                        $trans_title .= " // ".$article->getTitle();
                        $trans_content.=" // ".$article->getContent();
                    }

                    $article->setTitle($trans_title);
                    $article->setContent($trans_content);

                }
            }
            else if(DataFactory::getInstance()->isAnInstance($data,DataFactory::TYPE_LIST_STRING))
            {

                foreach($data->getContent() as $data_str)
                {
                    $trans_string = $this->request($data_str->getContent(),$input_lang,$output_lang);
                    if($keepOriginal)
                    {
                        $trans_string .= " // ".$data_str->getContent();
                    }

                    $data_str->setContent($trans_string);


                }
            }
            else if(DataFactory::getInstance()->isAnInstance($data,DataFactory::TYPE_ARTICLE))
            {

                $trans_title = $this->request($data->getTitle(),$input_lang,$output_lang);
                $trans_content = $this->request($data->getContent(),$input_lang,$output_lang);
                if($keepOriginal)
                {
                    $trans_title .= " // ".$data->getTitle();
                    $trans_content.=" // ".$data->getContent();
                }

                $data->setTitle($trans_title);
                $data->setContent($trans_content);
            }
            else if(DataFactory::getInstance()->isAnInstance($data,DataFactory::TYPE_STRING))
            {
                $trans_string = $this->request($data->getContent(),$input_lang,$output_lang);
                if($keepOriginal)
                {
                    $trans_string .= " // ".$data->getContent();
                }

                $data->setContent($trans_string);


            }

        }
        catch(\Exception $e)
        {
            $this->logger->err("[TranslateService] Error while translating data :".$e->getCode()." : ".$e->getMessage());

            throw $e;
        }

        return $data;
    }

    /**
     * send request and return translated string => 500 CHAR max
     * @param $query
     * @param $input the input lang
     * @param $output the output string
     * @return String translation
     */
    public function request($query,$input_lang,$output_lang)
    {
        $langpair = urlencode($input_lang."|".$output_lang);
        $url = "http://api.mymemory.translated.net/get?q=%query%&langpair=".$langpair;
        $trans = null;
        if(strlen($query)>500)
        {
            //split query every 500 chars
            $array = str_split($query,500);
            foreach($array as $row)
            {
                $trans.= $this->request($row,$input_lang,$output_lang);
            }
        }
        else
        {
            $query = $query;
            $link = str_replace("%query%",urlencode($query),$url);

            $json = $this->webcrawlerService->jsonCall($link);
            if($json->responseStatus==200){
                $trans = $json->responseData->translatedText;
            }
            else{
                $this->logger->err("[TranslateService] Error while translating data : ".$json->responseData->translatedText);
                throw new ServiceOutOfOrderException();
            }
        }

        return $trans;
    }
}