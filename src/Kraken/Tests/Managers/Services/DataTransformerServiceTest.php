<?php

namespace Kraken\Managers\Services;

use Doctrine\Common\Collections\ArrayCollection;
use Kraken\Entities\Data\DataArticle;
use Kraken\Entities\Data\DataDate;
use Kraken\Entities\Data\DataList;
use Kraken\Entities\Task\TaskCrawlWeb;
use Kraken\Factories\DataFactory;

/**
 * Class DataTransformerServiceTest
 * @package Kraken\Managers\Services
 * @author epidoux
 * @version 1.0
 */
class DataTransformerServiceTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @covers Kraken\Managers\Services\DataTransformerService::transform
     */
    public function testTransform()
    {

        $kernel = static::createKernel();
        $kernel->boot();
        $logger = $kernel->getContainer()
            ->get('logger');

        $service = new DataTransformerService($logger);

        //transform ARTICLE TO...
            $data = new DataArticle();
            $data->setId(2);
            $data->setTitle("Lorem");
            $data->setContent("Ipsum");
            $data->setDate(new \DateTime("25-05-2011"));

            //STRING
            $this->assertInstanceOf('Kraken\Entities\Data\DataString',$service->transform($data,DataFactory::TYPE_STRING);

        //transform DATE TO ...
            $data = new DataDate();
            $data->setContent(new \DateTime("25-05-2011"));

            //STRING
            $this->assertInstanceOf('Kraken\Entities\Data\DataString',$service->transform($data,DataFactory::TYPE_STRING);


            //ARTICLE
            $this->assertInstanceOf('Kraken\Entities\Data\DataArticle',$service->transform($data,DataFactory::TYPE_ARTICLE);


        //transform INTEGER TO ...
        $data = new DataInteger();
        $data->setContent(55);

            //STRING
            $this->assertEquals("55",$service->transform($data,DataFactory::TYPE_STRING));

        //transform LIST_ARTICLE TO ...
        $data = new DataList();
        $d = new DataArticle();
        $col = new ArrayCollection();
        $col->add($d);
        $data->setContent($col);

            //LIST_STRING
            $this->assertInstanceOf('Kraken\Entities\Data\DataList',$service->transform($data,DataFactory::TYPE_LIST_STRING);

            //STRING
            $this->assertInstanceOf('Kraken\Entities\Data\DataString',$service->transform($data,DataFactory::TYPE_STRING);


        //transform LIST_STRING TO ..
        $data = new DataList();
        $d = new DataString();
        $col = new ArrayCollection();
        $col->add($d);
        $data->setContent($col);

            //STRING
            $this->assertInstanceOf('Kraken\Entities\Data\DataString',$service->transform($data,DataFactory::TYPE_STRING);



        //transform STRING TO

            //???

    }



}
