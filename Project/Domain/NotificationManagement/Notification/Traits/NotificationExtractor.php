<?php

namespace Project\Domain\NotificationManagement\Notification\Traits;

use Project\UserInterface\Helpers\Utility;

trait NotificationExtractor
{
    /**
     * Inject transparent image.
     *
     * @param int $recordId
     * @return string
     */
    protected function getImageReadTag(int $recordId) : string
    {
        // Inject transparent image.
        return '<img src="' . route('notification-email-history.verify', ['code' => Utility::encode($recordId)]) . '" alt="" width="1px" height="1px" />';
    }

    /**
     * Get search item
     *
     * @param string $linkItem
     * @param string $item
     * @return array
     */
    protected function getSearchItem(string $linkItem, string $item): array
    {
        return [
            "<br/>" . $linkItem => "<br/>" . $item ,
            $linkItem . "<br/>" => $item . "<br/>" ,
            "<br>" . $linkItem => "<br>" . $item ,
            $linkItem . "<br>" => $item . "<br>" ,
            "\n" . $linkItem => "\n" . $item ,
            $linkItem . "\n" => $item . "\n" ,
            "\n\n" . $linkItem => "\n\n" . $item ,
            $linkItem . "\n\n" => $item . "\n\n" ,
            "\r" . $linkItem => "\r" . $item ,
            $linkItem . "\r" => $item . "\r" ,
            "\r\n" . $linkItem => "\r\n" . $item ,
            $linkItem . "\r\n" => $item . "\r\n" ,
            "\n\r" . $linkItem => "\n\r" . $item ,
            $linkItem . "\n\r" => $item . "\n\r" ,
            "\r\r" . $linkItem => "\r\r" . $item ,
            $linkItem . "\r\r" => $item . "\r\r" ,
            " " . $linkItem => " " . $item ,
            $linkItem . " " => $item . " " ,
        ];
    }

    /**
     * @return void
     */
    protected function convertLineBreaks() : void
    {
        $this->content = nl2br($this->content);
    }

    /**
     * @return void
     */
    protected function convertTextLinks() : void
    {
        if ($this->content != null)
        {
            preg_match_all('#\bhttps?://[^,\s()<>]+(?:\([\w\d]+\)|([^,[:punct:]\s]|/))#' , $this->content , $match);
            $link = [];
            foreach ($match[0] as $item)
            {
                $linkItem = "<a href='" . $item . "'>" . $item . "</a>";
                $searchItem = self::getSearchItem($linkItem, $item);
                self::applyTextLink($searchItem, $link, $linkItem);
            }
        }
    }

    /**
     * @param array $searchItem
     * @param array $link
     * @param string $linkItem
     */
    protected function applyTextLink(array $searchItem, array $link, string $linkItem) : void
    {
        foreach ($searchItem as $key => $value)
            if (!in_array($linkItem, $link))
            {
                $link[] = $key;
                $this->content = str_replace($value , $key , $this->content);
            }
    }
}
