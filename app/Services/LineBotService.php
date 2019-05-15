<?php
namespace App\Services;

use Log;
use LINE\LINEBot;
use LINE\LINEBot\Response;
use LINE\LINEBot\MessageBuilder;
use LINE\LINEBot\MessageBuilder\MultiMessageBuilder;
use LINE\LINEBot\MessageBuilder\ImageMessageBuilder;
use LINE\LINEBot\MessageBuilder\TemplateMessageBuilder;
use LINE\LINEBot\MessageBuilder\TextMessageBuilder;
use LINE\LINEBot\TemplateActionBuilder\UriTemplateActionBuilder;
use LINE\LINEBot\MessageBuilder\TemplateBuilder\ImageCarouselColumnTemplateBuilder;
use LINE\LINEBot\MessageBuilder\TemplateBuilder\ImageCarouselTemplateBuilder;

class LineBotService
{
    /** @var LINEBot */
    private $lineBot;
    private $lineUserId;

    public function __construct($lineUserId)
    {
        $this->lineUserId = $lineUserId;
        $this->lineBot = app(LINEBot::class);
    }

    public function fake()
    {
    }

    /**
     * @param TemplateMessageBuilder|string $content
     * @return Response
     */
    public function pushMessage($content): Response
    {
        if (is_string($content)) {
            $content = new TextMessageBuilder($content);
        }
        return $this->lineBot->pushMessage($this->lineUserId, $content);
    }
    public function buildTemplateMessageBuilderDeprecated(
        string $imagePath,
        string $directUri,
        string $label,
        string $noticeText): TemplateMessageBuilder
    {
        $aa = new UriTemplateActionBuilder($label, $directUri);
        $bb =  new ImageCarouselColumnTemplateBuilder($imagePath, $aa);
        $target = new ImageCarouselTemplateBuilder([$bb]);
        $templateMessage = new TemplateMessageBuilder($noticeText, $target);
        $this->pushMessage($templateMessage);
        return $templateMessage;
    }

    public function MultiMessageBuilder(string $originalContentUrl,string $previewImageUrl)
    {
        $messageBuilder = new MultiMessageBuilder();
        $messageBuilder->add(new ImageMessageBuilder(
            $originalContentUrl,
            $previewImageUrl
        ));
        $this->pushMessage($messageBuilder);
        return $messageBuilder;
    }
    /**
     * @param array $data
     *              [
     *                  [
     *                      'imagePath' => string,
     *                      'directUri' => string,
     *                      'label' => string,
     *                  ],
     *              ]
     * @param string $notificationText
     * @return array Array of TemplateMessageBuilder
     */
    public function buildTemplateMessageBuilder(array $data, string $notificationText = '新通知來囉!'): array
    {
        $imageCarouselColumnTemplateBuilders = array_map(function ($d) {
            return $this->buildImageCarouselColumnTemplateBuilder(
                $d['imagePath'],
                $d['directUri'],
                $d['label']
            );
        }, $data);
        $tempChunk = array_chunk($imageCarouselColumnTemplateBuilders, 5);
        return array_map(function ($data) use ($notificationText) {
            return new TemplateMessageBuilder(
                $notificationText,
                new ImageCarouselTemplateBuilder($data)
            );
        }, $tempChunk);
    }
    /**
     * @param string $imagePath
     * @param string $directUri
     * @param string $label
     * @return ImageCarouselColumnTemplateBuilder
     */
    protected function buildImageCarouselColumnTemplateBuilder(
        string $imagePath,
        string $directUri,
        string $label
    ): ImageCarouselColumnTemplateBuilder {
        return new ImageCarouselColumnTemplateBuilder(
            $imagePath,
            new UriTemplateActionBuilder($label, $directUri)
        );
    }
}