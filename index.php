<h3><b><u>免责协议：</u></b></h3><br>
<b>**您在阅读网页正文时，就表明您一同意该免责协议。**</b><br>
1.网页的内容来自BBC News（bbc.com），网页正文的版权以及解释权归BBC所有，同时网页正文内容的观点及立场并不代表网站开发者的观点和立场。<br>
2.网页纯属用于交流学习，切忌将网页用于非法用途或违背道德的用途。<br>
3.不正确使用网站所造成的后果，开发者将一概不负责任！<br>
**若您对以上协议有任何异议，请不要继续往下阅读，并关闭网页，谢谢支持！**<br>
--------------------分割线（以下为正文）--------------------------<br>
<?php
function section($section,$document){
    if($section=="hot"){
        $pattern =  '/\<section class="module module--news   module--collapse-images"\>(.*?)\<\/section\>/s';
    }
    else{
        $error=[
            0=>false,
            1=>"Unknown Section Type!"
        ];
        return($error);
    }
    if(preg_match_all($pattern, $document, $matches)){  
        //print_r($matches);  
        $news=$matches[0][0];
        $news=[
            0=>true,
            1=>$news
        ];
        return($news);
     }else{  
        $error=[
            0=>false,
            1=>"Error,The section not found!"
        ];
        return($error);
     }
}

function get_urls($html){
    $pattern =  '/\<a class="block-link__overlay-link"(.*?)\<\/a\>/s';
    if(preg_match_all($pattern, $html, $matches)){  
        $urls=$matches[0];
        $urls=[
            0=>true,
            1=>$urls
        ];
        return($urls);
     }else{  
        $error=[
            0=>false,
            1=>"Error,urls are not found!"
        ];
        return($error);
     }
}

function get_news_countent($html){
    $pattern =  '/<a .*?href="(.*?)".*?>/is';
    if(preg_match_all($pattern, $html, $matches)){  
        $url="http://bbc.com/".$matches[1][0]."/";
        $content=file_get_contents($url);

        $pattern =  '/\<h1 class="story-body__h1">(.*?)\<\/h1\>/s';
        if(preg_match_all($pattern, $content, $matches)){
            echo("<h3>");//文章标题。
            echo $matches[1][0];
            echo("</h3>");
            
            $pattern =  '/<p class="story-body__introduction">(.*?)<p/';
            if(preg_match_all($pattern, $content, $matches)){
                echo($matches[1][0]."</br>");
                
                
                $pattern =  '/<p>(.*?)<a/';
                if(preg_match_all($pattern, $content, $matches)){
                    echo($matches[1][0]."</br>");
                }
                else{
                $pattern =  '/<p>(.*?)<strong/';
                if(preg_match_all($pattern, $content, $matches)){
                    echo($matches[1][0]."</br>");
                }
                else{
                $pattern =  '/<p>(.*?)<div/';
                if(preg_match_all($pattern, $content, $matches)){
                    echo($matches[1][0]."</br>");
                }
                else{
                $pattern =  '/<p>(.*?)<lu/';
                if(preg_match_all($pattern, $content, $matches)){
                    echo($matches[1][0]."</br>");
                }
                else{
                    $pattern =  '/<p>(.*?)<img/';
                    if(preg_match_all($pattern, $content, $matches)){
                        echo($content);
                    }
                }
                }
            }
            }
            }
            else{
                ////////////////////////
            }
        }
        else{
            $error=[
                0=>false,
                1=>"Error,title in the article is not found!"
            ];
            return($error);
        }
    }
    else{
        $error=[
            0=>false,
            1=>"Error,urls2 are not found!"
        ];
        return($error);
    }
}

$url="http://www.bbc.com/";
$document=file_get_contents($url);
if(!isset($document)||$document==null){
    $error_code="Webpage '$url' not found!(请尝试刷新网页，或更换新闻来源。）";
    require_once("error.php");
}//判断网页是否能打开

//<section class="module module--news   module--collapse-images">
$section=(section("hot",$document));
if($section[0]==false){
    $error_code=$section[1];
    require_once("error.php");
}
else{
    $section=$section[1];
}

$urls=(get_urls($section));
$count=count($urls[1])+1;
echo("There are $count news are found</br>");
if($count<2){
    $error_code="The amount of the news which the programe find is less than the minimal value.(请尝试更换来源或者类型！)";
    require("error.php");
}//检查程序所找到的新闻数是否大于最小值2。

$news1=$urls[1][0];
$news2=$urls[1][1];

echo("<br><br>------------------------<br><h1>News 1:</h1><br>");
get_news_countent($news1);
echo("<br><br>------------------------<br><h1>News 2:</h1><br>");
get_news_countent($news2);
