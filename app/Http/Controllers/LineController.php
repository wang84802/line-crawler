<?php

namespace App\Http\Controllers;

use Log;
use LINE;
use LINE\LINEBot;
use Illuminate\Http\Request;
use GuzzleHttp\Client;
use App\Services\LineBotService;
use App\Services\CrawlerService;
use Symfony\Component\DomCrawler\Crawler;

class LineController extends Controller
{
    public function __construct(LineBotService $LineBotService , CrawlerService $CrawlerService)
    {
        $this->LineBotService = $LineBotService;
        $this->CrawlerService = $CrawlerService;
    }

    public function push()
    {
        $this->LineBotService->pushMessage('123');
    }

    public function pushImage(Request $request)
    {
        $data = $request->data;
        $this->LineBotService->buildTemplateMessageBuilderDeprecated(
            $data['imagePath'],$data['directUri'],$data['label'],$data['noticeText']
        );
    }

    public function pushMultiImage()
    {
        $originalContentUrl = 'https://login.apiary.io/login';
        $previewImageUrl = 'https://imgur.com/QVKP6Rc.jpg';
        $this->LineBotService->MultiMessageBuilder($originalContentUrl, $previewImageUrl);
    }

    public function one_piece()
    {
        $path = "https://one-piece.cn/comic/";
        $result = $this->CrawlerService->crawl_one_piece($path);

        if($result){
            //dd($label = '第'.$result[1].'話 '.$result[0]);
            $imagePath = "https://i.imgur.com/RRk9rZo.jpg";
            $directUri = 'https://one-piece.cn/post/10'.$result[1].'/';
            $label = $result[1];
            $noticeText = "One piece updated !!!";
            $this->LineBotService->buildTemplateMessageBuilderDeprecated(
                $imagePath,$directUri,$label,$noticeText
            );
            return $result;
        }
        Log::info('One piece not update yet.');
        return 'One piece not update yet.';
    }

    public function horny_dragon()
    {
        $path = "https://hornydragon.blogspot.com/search/label/%E9%9B%9C%E4%B8%83%E9%9B%9C%E5%85%AB%E7%9F%AD%E7%AF%87%E6%BC%AB%E7%95%AB%E7%BF%BB%E8%AD%AF";
        $result = $this->CrawlerService->crawl_horny_dragon($path);
        if($result){
            $year = getdate()['year'];
            $mon = str_pad(getdate()['mon'],2,'0',STR_PAD_LEFT);
            $imagePath = "https://i.imgur.com/QVKP6Rc.jpg";
            $directUri = 'https://hornydragon.blogspot.com/'.$year.'/'.$mon.'/'.$result.'.html';
            $label = $result;
            $noticeText = "Horny dragon updated !!!";
            $this->LineBotService->buildTemplateMessageBuilderDeprecated(
                $imagePath,$directUri,$label,$noticeText
            );
        }
        Log::info('Horny dragon not update yet.');
        return 'Horny dragon not update yet.';
    }
}
