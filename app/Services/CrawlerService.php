<?php
namespace App\Services;

use App\One_piece;
use App\Horny_dragon;
use GuzzleHttp\Client;
use fengqi\Hanzi\Hanzi;
use Symfony\Component\DomCrawler\Crawler;

class CrawlerService
{
    /**
     * @param string $path
     * @return Crawler
     */
    public function crawl_one_piece($url)
    {
        $crawler = $this->crawler($url);

        $target = $crawler->filterXPath('//*[@id="tab-main"]')->text();
        $array = mb_split(" ", $target);

        $data = One_piece::orderBy('id', 'DESC')->first();
        $newest_db = $data->id;

        $exist_in_target = strpos($target, strval($newest_db));
        $newest_comic_updated = strpos($target, strval($newest_db+1));

        if (($exist_in_target != false) && ($newest_comic_updated == false))
            return false;
        else {
            $newest_name_online = $array[count($array) - 1];
            $newest_name_online = str_replace("\r\n","",$newest_name_online);

            One_piece::create(['id'=>$newest_db+1,'name'=>Hanzi::turn($newest_name_online)]);
            return [Hanzi::turn($newest_name_online),($newest_db+1)];
        }
    }

    public function crawl_horny_dragon($url)
    {
        $crawler = $this->crawler($url);

        $target = $crawler->filterXPath('//*[@class="post-outer"]')->first()->text();

        $target = str_replace("\n","",$target);

        $data = Horny_dragon::orderBy('id', 'DESC')->first();
        $newest_db = $data->id;

        $exist_in_target = strpos($target, strval($newest_db));
        $newest_comic_updated = strpos($target, strval($newest_db+1));

        if (($exist_in_target != false) && ($newest_comic_updated == false))
            return false;
        else {
            Horny_dragon::create(['id'=>$newest_db+1]);
            return $newest_db+1;
        }
    }
   
    public function crawl_promised_neverland($url)
    {
        $data = Promised_neverland::orderBy('id', 'DESC')->first();
        $newest_db = $data->id;
        //dd($newest_db);
        $crawler = $this->crawler($url);

        $target_href = $crawler->filterXPath('//*[@id="chapter-list-1"]');
        $target_href = $target_href->filterXPath('//*[@target="_blank"]')->last()->attr("href");

        $target = $crawler->filterXPath('//*[@id="chapter-list-1"]');
        $target = $target->filterXPath('//*[@target="_blank"]')->last()->text();
        $target = str_replace("\n","",$target);
        $target = str_replace("\r","",$target);
        $target = str_replace("新","",$target);
        $target = trim("$target");
        $newest_name_online  = mb_split(" ", $target,2);
        $newest_name_online = Hanzi::turn($newest_name_online[1]);

        $exist_in_target = strpos($target, strval($newest_db)); //false=>沒有出現 [number]=>出現位置
        //dd($exist_in_target);
        $newest_comic_updated = strpos($target, strval($newest_db+1));


        if (($exist_in_target === false) && ($newest_comic_updated === 0))
        {
            Promised_neverland::create([
                'id'=>$newest_db+1,
                'name'=>$newest_name_online
            ]);
            return [$newest_db+1,$newest_name_online,$target_href];
        }

        else {
            return false;
        }
    }
    
    public function crawl_fang_ji($url)
    {
        $crawler = $this->crawler($url);
        $target_name = $crawler->filterXPath('//*[@class="post-title entry-title"]')->text();

        if (strpos($target_name,'Part')) {
            $target_href = $crawler->filterXPath('//*[@class="post-title entry-title"]')->filter('a')->attr('href');
            $a = Storage::disk('local')->exists('fang_ji/'.$target_name);
            if($a == true) // local exist
                return false;
            else{
                Storage::disk('local')->put('fang_ji/'.$target_name, $target_href);
                return[$target_href,$target_name];
            }
        }else
            return false;
    }


    protected function crawler($url){
        $client = new Client([
            'timeout' => 10,
            'headers' => [
                'User-Agent' => 'Mozilla/5.0 (compatible; Baiduspider-render/2.0; +http://www.baidu.com/search/spider.html)',
            ],
        ]);
        $response = $client->request('GET', $url)->getBody()->getContents();

        //XPATH數據抽取
        $crawler = new Crawler();
        $crawler->addHtmlContent($response);
        return $crawler;
    }
}
