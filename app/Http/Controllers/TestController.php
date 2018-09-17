<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Curl;
use Illuminate\Support\Facades\Input;

class TestController extends Controller
{
    public function curlTest(){
        //json数据流请求

//        $data = array("token" => "8d4f7b95d0c5093ee121e6d001a4f5cb","version" => "1.0.1","limit"=>10,"page"=>1);
//        $curl = new Curl();
//        $data = $curl->jsonPostData('my_live_courses',$data);
//        return $data;


        //数组post请求
//        $data = array("token" => "c5244f1897ff00dbcfedaa1634681761","version" => "1.0.1","limit"=>10,"page"=>1);
//        $curl = new Curl();
//        $res = $curl->arrayPostData('test',$data);
//        return $res;


        //数组get请求
//        $data = array("token" => "c5244f1897ff00dbcfedaa1634681761","version" => "1.0.1","limit"=>10,"page"=>1);
//        $curl = new Curl();
//        $res = $curl->arrayGetData('test',$data);
//        return $res;

        //xss攻击测试
        $str = '<script>alert(11);</script>';
        clean_xss($str);
        return $str;

    }

    public  function imTest(){
        $im = new ImServerApi();
        $roominfo = $im->GetChatRoom('25069450');
        return $roominfo;
    }

    public function set_question(){
        $data = [
            [
                'question_id'    => '1',
                'question_type'  => '1',
                'question_title' => '诗句[千门万户瞳瞳日，总把新桃换旧符]中的[桃]是什么意思？',
                'answer_list'    => [
                                        ['answer_key'=>'A','answer_val'=>'桃符'],
                                        ['answer_key'=>'B','answer_val'=>'桃木剑'],
                                        ['answer_key'=>'C','answer_val'=>'寿桃馒头'],
                                        ['answer_key'=>'D','answer_val'=>'桃花']
                                      ],
                'answer'         => 'A',
                'analysis'       => '初升的太阳照耀着千家万户,他们都忙着把旧的桃符取下,换上新的桃符。你真的很优秀哦！棒棒的'
            ]
            ,
            [
                'question_id'    => '2',
                'question_type'  => '1',
                'question_title' => '徐志摩笔下[康桥]位于英国哪所大学？',
                'answer_list'    =>[
                                        ['answer_key'=>'A','answer_val'=>'伦敦大学'],
                                        ['answer_key'=>'B','answer_val'=>'牛津大学'],
                                        ['answer_key'=>'C','answer_val'=>'剑桥大学'],
                                        ['answer_key'=>'D','answer_val'=>'哈佛大学']
                                    ],
                'answer'         => 'B',
                'analysis'       => '是不是点“剑桥大学”啊！啊哈哈哈，我也没反应过来。'
            ]
            ,
            [
                'question_id'    => '3',
                'question_type'  => '1',
                'question_title' => '名著《三国演义》中，谁劝孙权将荆州借给刘备？',
                'answer_list'    => [
                                        ['answer_key'=>'A','answer_val'=>'吕蒙'],
                                        ['answer_key'=>'B','answer_val'=>'周瑜'],
                                        ['answer_key'=>'C','answer_val'=>'陆逊'],
                                        ['answer_key'=>'D','answer_val'=>'鲁肃']
                                    ],
                'answer'         => 'D',
                'analysis'       => '敢不敢承认自己查了百度啊！'
            ]
            ,
            [
                'question_id'    => '4',
                'question_type'  => '1',
                'question_title' => '用算盘进行运算称为什么算？',
                'answer_list'    =>[
                                        ['answer_key'=>'A','answer_val'=>'珠算'],
                                        ['answer_key'=>'B','answer_val'=>'盘算'],
                                        ['answer_key'=>'C','answer_val'=>'指算'],
                                        ['answer_key'=>'D','answer_val'=>'心酸']
                                    ],
                'answer'         => 'A',
                'analysis'       => '可以啊！小时候是不是特意学习过。'
            ]
            ,
            [
                'question_id'    => '5',
                'question_type'  => '1',
                'question_title' => '有着狂人称号的足球教练是？',
                'answer_list'    => [
                                        ['answer_key'=>'A','answer_val'=>'安切洛蒂'],
                                        ['answer_key'=>'B','answer_val'=>'穆里尼奥'],
                                        ['answer_key'=>'C','answer_val'=>'瓜迪奥拉'],
                                        ['answer_key'=>'D','answer_val'=>'贝尼特斯']
                                    ],
                'answer'         => 'B',
                'analysis'       => '不喜欢足球的人，第一次肯定答不对'
            ]
            ,
            [
                'question_id'    => '6',
                'question_type'  => '1',
                'question_title' => '被尊称为乐圣的音乐家是？',
                'answer_list'    => [
                                        ['answer_key'=>'A','answer_val'=>'亨德尔'],
                                        ['answer_key'=>'B','answer_val'=>'贝多芬'],
                                        ['answer_key'=>'C','answer_val'=>'巴赫'],
                                        ['answer_key'=>'D','answer_val'=>'莫扎特']
                                    ],
                'answer'         => 'B',
                'analysis'       => '天才啊！琴棋书画，样样精通啊！'
            ]
            ,
            [
                'question_id'    => '7',
                'question_type'  => '1',
                'question_title' => '美国男子职业篮球联赛的英文缩写是？',
                'answer_list'    => [
                                        ['answer_key'=>'A','answer_val'=>'NBA'],
                                        ['answer_key'=>'B','answer_val'=>'CBA'],
                                        ['answer_key'=>'C','answer_val'=>'NBDL'],
                                        ['answer_key'=>'D','answer_val'=>'NCAA']
                                    ],
                'answer'         => 'A',
                'analysis'       => '你能看到我的小眼神吗！一点都不觉得惊讶。'
            ]
            ,
            [
                'question_id'    => '8',
                'question_type'  => '1',
                'question_title' => '<在地愿为连理枝>的上一句是？',
                'answer_list'    => [
                                        ['answer_key'=>'A','answer_val'=>'在天愿作比翼鸟'],
                                        ['answer_key'=>'B','answer_val'=>'但教心似金钿坚'],
                                        ['answer_key'=>'C','answer_val'=>'昭阳殿里恩爱绝'],
                                        ['answer_key'=>'D','answer_val'=>'风吹仙袂飘飖举']
                                    ],
                'answer'         => 'A',
                'analysis'       => '小学语文没白学啊！'
            ]
            ,
            [
                'question_id'    => '9',
                'question_type'  => '1',
                'question_title' => '下面那首歌的原唱歌曲是由某一乐队合唱的而非个人独唱？',
                'answer_list'    => [
                                        ['answer_key'=>'A','answer_val'=>'海阔天空'],
                                        ['answer_key'=>'B','answer_val'=>'新不了情'],
                                        ['answer_key'=>'C','answer_val'=>'水手'],
                                        ['answer_key'=>'D','answer_val'=>'女儿情']
                                    ],
                'answer'         => 'A',
                'analysis'       => '你是80后？90后？还是00后？'
            ]
            ,
            [
                'question_id'    => '10',
                'question_type'  => '1',
                'question_title' => '下面那首诗歌的作者不是海子？',
                'answer_list'    => [
                                        ['answer_key'=>'A','answer_val'=>'麦地'],
                                        ['answer_key'=>'B','answer_val'=>'回答'],
                                        ['answer_key'=>'C','answer_val'=>'以梦为马'],
                                        ['answer_key'=>'D','answer_val'=>'亚洲铜']
                                    ],
                'answer'         => 'B',
                'analysis'       => '我看出来了，你居然还是个文艺青年哈！'
            ]
            ,
            [
                'question_id'    => '11',
                'question_type'  => '1',
                'question_title' => '中国男子职业篮球联赛的英文缩写是？',
                'answer_list'    => [
                                        ['answer_key'=>'A','answer_val'=>'NBA'],
                                        ['answer_key'=>'B','answer_val'=>'CBA'],
                                        ['answer_key'=>'C','answer_val'=>'NBDL'],
                                        ['answer_key'=>'D','answer_val'=>'NCAA']
                                    ],
                'answer'         => 'B',
                'analysis'       => '幸好你答出来了。不然我怕我笑出声，吓死你！'
            ]
            ,
            [
                'question_id'    => '12',
                'question_type'  => '1',
                'question_title' => '<两耳不闻窗外事>的下一句事？',
                'answer_list'    => [
                                        ['answer_key'=>'A','answer_val'=>'至今已觉不新鲜'],
                                        ['answer_key'=>'B','answer_val'=>'春风不度玉门关'],
                                        ['answer_key'=>'C','answer_val'=>'一心只读圣贤书'],
                                        ['answer_key'=>'D','answer_val'=>'潮打空城寂寞回']
                                    ],
                'answer'         => 'C',
                'analysis'       => '俗话说“两耳不闻窗外事，一心只读圣贤书”圣贤书啊！书没白读，哈哈！'
            ]
            ,
            [
                'question_id'    => '13',
                'question_type'  => '1',
                'question_title' => '<烟开兰叶香风暖>的下一句是？',
                'answer_list'    => [
                                        ['answer_key'=>'A','answer_val'=>'长洲孤月向谁明'],
                                        ['answer_key'=>'B','answer_val'=>'迁客此时徒极目'],
                                        ['answer_key'=>'C','answer_val'=>'岸夹桃花锦浪生'],
                                        ['answer_key'=>'D','answer_val'=>'鹦鹉来过吴江水']
                                    ],
                'answer'         => 'C',
                'analysis'       => '有时间多读书！总是查上网搜，也不是个办法！'
            ]
            ,
            [
                'question_id'    => '14',
                'question_type'  => '1',
                'question_title' => '<青松霭朝霞>的下一句是？',
                'answer_list'    => [
                                        ['answer_key'=>'A','answer_val'=>'缥缈山丁村'],
                                        ['answer_key'=>'B','answer_val'=>'长啸祭昆仑'],
                                        ['answer_key'=>'C','answer_val'=>'星斗俯可扪'],
                                        ['answer_key'=>'D','answer_val'=>'日与化工进']
                                    ],
                'answer'         => 'A',
                'analysis'       => '这一题，答不出来，情有可原啊！因为我也不知道。'
            ]
            ,
            [
                'question_id'    => '15',
                'question_type'  => '1',
                'question_title' => '下面不属于亚洲两州的分界线的是？',
                'answer_list'    => [
                                        ['answer_key'=>'A','answer_val'=>'乌拉尔山'],
                                        ['answer_key'=>'B','answer_val'=>'黑海'],
                                        ['answer_key'=>'C','answer_val'=>'里海'],
                                        ['answer_key'=>'D','answer_val'=>'白令海峡']
                                    ],
                'answer'         => 'D',
                'analysis'       => '答不出来的，地理估计是音乐老师教的吧！'
            ]
            ,
            [
                'question_id'    => '16',
                'question_type'  => '1',
                'question_title' => '电影《泰坦尼克号》的男主角的饰演者是？',
                'answer_list'    => [
                                        ['answer_key'=>'A','answer_val'=>'汤姆·克鲁斯'],
                                        ['answer_key'=>'B','answer_val'=>'莱昂纳多·迪卡普里奥'],
                                        ['answer_key'=>'C','answer_val'=>'汤姆·汉克斯'],
                                        ['answer_key'=>'D','answer_val'=>'基努·里维斯']
                                    ],
                'answer'         => 'B',
                'analysis'       => '现在还能记住他的人，估计都是80后和90后了吧！因为当时小李子年轻的时候，比国内的小鲜肉帅多了'
            ]
            ,
            [
                'question_id'    => '17',
                'question_type'  => '1',
                'question_title' => '《跳房子》游戏最早起源于下列哪项活动？',
                'answer_list'    => [
                                        ['answer_key'=>'A','answer_val'=>'军事训练'],
                                        ['answer_key'=>'B','answer_val'=>'宫廷歌舞'],
                                        ['answer_key'=>'C','answer_val'=>'宫廷歌舞'],
                                        ['answer_key'=>'D','answer_val'=>'商品买卖']
                                    ],
                'answer'         => 'A',
                'analysis'       => '哈哈哈！游戏来源于生活啊！'
            ]
            ,
            [
                'question_id'    => '18',
                'question_type'  => '1',
                'question_title' => '《亚洲周刊》评选的（二十世纪中文小说一百强）中，排名第一的是?',
                'answer_list'    => [
                                        ['answer_key'=>'A','answer_val'=>'呐喊'],
                                        ['answer_key'=>'B','answer_val'=>'射雕英雄传'],
                                        ['answer_key'=>'C','answer_val'=>'边城'],
                                        ['answer_key'=>'D','answer_val'=>'围城']
                                    ],
                'answer'         => 'A',
                'analysis'       => '没事多读读书，多关注关注新闻！别一天到晚追剧，看综艺了。'
            ]
            ,
            [
                'question_id'    => '19',
                'question_type'  => '1',
                'question_title' => '下面那个是习语（down-and-outs）的中文解释？',
                'answer_list'    => [
                                        ['answer_key'=>'A','answer_val'=>'无家可归的人'],
                                        ['answer_key'=>'B','answer_val'=>'局外人'],
                                        ['answer_key'=>'C','answer_val'=>'大起大落的人'],
                                        ['answer_key'=>'D','answer_val'=>'生活艰辛的人']
                                    ],
                'answer'         => 'A',
                'analysis'       => '这个不想解释，我这种英语白痴，也是一脸懵逼的状态。'
            ]
            ,
            [
                'question_id'    => '20',
                'question_type'  => '1',
                'question_title' => '<在天愿做比翼鸟，在地愿为连理枝>这句诗描述的是哪两位的爱情故事？',
                'answer_list'    => [
                                        ['answer_key'=>'A','answer_val'=>'李隆基和杨玉环'],
                                        ['answer_key'=>'B','answer_val'=>'焦仲卿和刘兰芝'],
                                        ['answer_key'=>'C','answer_val'=>'梁山伯和祝英台'],
                                        ['answer_key'=>'D','answer_val'=>'张君瑞和崔莺莺']
                                    ],
                'answer'         => 'A',
                'analysis'       => '00后和90后知道吗？我是不是有点为难她们！小时候我还陪我爷爷一起看黄梅戏，庐剧呢！'
            ]
            ,
            [
                'question_id'    => '21',
                'question_type'  => '1',
                'question_title' => '黄海冰主演电视剧《隋唐英雄传》中，（混世魔王）程咬金的扮演者是？',
                'answer_list'    => [
                                        ['answer_key'=>'A','answer_val'=>'任山'],
                                        ['answer_key'=>'B','answer_val'=>'卓凡'],
                                        ['answer_key'=>'C','answer_val'=>'林子聪'],
                                        ['answer_key'=>'D','answer_val'=>'黄海冰']
                                    ],
                'answer'         => 'C',
                'analysis'       => '这道题我感觉，也是属于80后和90后那个时代的东西吧！'
            ]
            ,
            [
                'question_id'    => '22',
                'question_type'  => '1',
                'question_title' => '下列哪项不属于人体主要供能的三大营养素？',
                'answer_list'    => [
                                        ['answer_key'=>'A','answer_val'=>'碳水化合物'],
                                        ['answer_key'=>'B','answer_val'=>'脂肪'],
                                        ['answer_key'=>'C','answer_val'=>'无机盐'],
                                        ['answer_key'=>'D','answer_val'=>'蛋白质']
                                    ],
                'answer'         => 'C',
                'analysis'       => '当然是无机盐啊！无机盐俗称矿物质！在生物细胞内一般只占鲜重的1%至1.5%。'
            ]
            ,
            [
                'question_id'    => '23',
                'question_type'  => '1',
                'question_title' => '德国在19世纪初所构建的哲学体系，又被称为？',
                'answer_list'    => [
                                        ['answer_key'=>'A','answer_val'=>'浪漫主义哲学'],
                                        ['answer_key'=>'B','answer_val'=>'古典哲学'],
                                        ['answer_key'=>'C','answer_val'=>'现代哲学'],
                                        ['answer_key'=>'D','answer_val'=>'现实主义哲学']
                                    ],
                'answer'         => 'B',
                'analysis'       => '答错了的人一看就是高中历史没及格过。'
            ]
            ,
            [
                'question_id'    => '24',
                'question_type'  => '1',
                'question_title' => '酥油茶是中国哪个名族的特色饮料？',
                'answer_list'    => [
                                        ['answer_key'=>'A','answer_val'=>'藏族'],
                                        ['answer_key'=>'B','answer_val'=>'维吾尔族'],
                                        ['answer_key'=>'C','answer_val'=>'彝族'],
                                        ['answer_key'=>'D','answer_val'=>'蒙古族']
                                    ],
                'answer'         => 'A',
                'analysis'       => '你们没去过青藏高原，不知道情有可原！想当年......我其实也没去过emmmmm'
            ]
            ,
            [
                'question_id'    => '25',
                'question_type'  => '1',
                'question_title' => '喜剧电影《羞羞的铁拳》中，马小和谁交换了身体？',
                'answer_list'    => [
                                        ['answer_key'=>'A','answer_val'=>'马东'],
                                        ['answer_key'=>'B','answer_val'=>'艾迪生'],
                                        ['answer_key'=>'C','answer_val'=>'吴良'],
                                        ['answer_key'=>'D','answer_val'=>'秀念']
                                    ],
                'answer'         => 'B',
                'analysis'       => '答错了的人，一看就是没有对象，而且还没有娱乐生活！'
            ]
            ,
            [
                'question_id'    => '26',
                'question_type'  => '1',
                'question_title' => '下列哪首歌曲的主题曲是《思乡》？',
                'answer_list'    => [
                                        ['answer_key'=>'A','answer_val'=>'《敢问路在何方》'],
                                        ['answer_key'=>'B','answer_val'=>'《老男孩》'],
                                        ['answer_key'=>'C','answer_val'=>'《九月九的酒》'],
                                        ['answer_key'=>'D','answer_val'=>'《心雨》']
                                    ],
                'answer'         => 'C',
                'analysis'       => '九月九最具代表的就是《九月九的酒》啦！'
            ]
            ,
            [
                'question_id'    => '27',
                'question_type'  => '1',
                'question_title' => '被称为《世界屋脊》的是？',
                'answer_list'    => [
                                        ['answer_key'=>'A','answer_val'=>'云贵高原'],
                                        ['answer_key'=>'B','answer_val'=>'南极高原'],
                                        ['answer_key'=>'C','answer_val'=>'巴西高原'],
                                        ['answer_key'=>'D','answer_val'=>'青藏高原']
                                    ],
                'answer'         => 'D',
                'analysis'       => '天啦！要是谁答错了，自己去跪搓衣板啊。'
            ]
            ,
            [
                'question_id'    => '28',
                'question_type'  => '1',
                'question_title' => '营养不良一般不会出现下列哪种情况？',
                'answer_list'    => [
                                        ['answer_key'=>'A','answer_val'=>'皮肤黏膜红润'],
                                        ['answer_key'=>'B','answer_val'=>'皮肤弹性降低'],
                                        ['answer_key'=>'C','answer_val'=>'皮下脂肪减少'],
                                        ['answer_key'=>'D','answer_val'=>'肌肉松弛无力']
                                    ],
                'answer'         => 'A',
                'analysis'       => '这个我其实也不懂。'
            ]
            ,
            [
                'question_id'    => '29',
                'question_type'  => '1',
                'question_title' => '腾讯游戏成立于哪年？',
                'answer_list'    => [
                                        ['answer_key'=>'A','answer_val'=>'2012'],
                                        ['answer_key'=>'B','answer_val'=>'2004'],
                                        ['answer_key'=>'C','answer_val'=>'2000'],
                                        ['answer_key'=>'D','answer_val'=>'2003']
                                    ],
                'answer'         => 'D',
                'analysis'       => '喜欢玩游戏的，应该都会对腾讯有很深的了解。虽然我也只是知道腾讯总部在深圳。'
            ]
            ,
            [
                'question_id'    => '30',
                'question_type'  => '1',
                'question_title' => '初中数学的内容通常被分为代数和？',
                'answer_list'    => [
                                        ['answer_key'=>'A','answer_val'=>'几何'],
                                        ['answer_key'=>'B','answer_val'=>'概率'],
                                        ['answer_key'=>'C','answer_val'=>'方程'],
                                        ['answer_key'=>'D','answer_val'=>'积分']
                                    ],
                'answer'         => 'A',
                'analysis'       => '哇！选错了的人，看来你们数学很好啊！来来来1+2+3+......+99=?,3秒中告诉我答案！'
            ]
            ,
            [
                'question_id'    => '31',
                'question_type'  => '1',
                'question_title' => '<人无完人>的上一句是？',
                'answer_list'    => [
                                        ['answer_key'=>'A','answer_val'=>'千里远行'],
                                        ['answer_key'=>'B','answer_val'=>'百尺竿头'],
                                        ['answer_key'=>'C','answer_val'=>'金无足赤'],
                                        ['answer_key'=>'D','answer_val'=>'大智若愚']
                                    ],
                'answer'         => 'C',
                'analysis'       => '我说啥来着，多看书，好找对象！'
            ]
            ,
            [
                'question_id'    => '32',
                'question_type'  => '1',
                'question_title' => '木偶动画电影《阿凡提的故事》中阿凡提常骑着哪种动物？',
                'answer_list'    => [
                                        ['answer_key'=>'A','answer_val'=>'骡子'],
                                        ['answer_key'=>'B','answer_val'=>'驴'],
                                        ['answer_key'=>'C','answer_val'=>'马'],
                                        ['answer_key'=>'D','answer_val'=>'老虎']
                                    ],
                'answer'         => 'B',
                'analysis'       => '难不成阿凡提骑老虎啊！你咋不说他去大象呢！'
            ]
            ,
            [
                'question_id'    => '33',
                'question_type'  => '1',
                'question_title' => '雅思的简称是？',
                'answer_list'    => [
                                        ['answer_key'=>'A','answer_val'=>'IELT'],
                                        ['answer_key'=>'B','answer_val'=>'YELT'],
                                        ['answer_key'=>'C','answer_val'=>'YELTS'],
                                        ['answer_key'=>'D','answer_val'=>'IELTS']
                                    ],
                'answer'         => 'D',
                'analysis'       => '哇！在座的各位就从来没想过出国深造的嘛！'
            ]
            ,
            [
                'question_id'    => '34',
                'question_type'  => '1',
                'question_title' => '小说《封神演义》中，哪位著名的人物用直的鱼钩钓鱼？',
                'answer_list'    => [
                                        ['answer_key'=>'A','answer_val'=>'姜太公'],
                                        ['answer_key'=>'B','answer_val'=>'雷震子'],
                                        ['answer_key'=>'C','answer_val'=>'哪吒'],
                                        ['answer_key'=>'D','answer_val'=>'比干']
                                    ],
                'answer'         => 'A',
                'analysis'       => '咱们国家经典的传说，还有谒后语！居然都能答错，我也是福气了。'
            ]
            ,
            [
                'question_id'    => '35',
                'question_type'  => '1',
                'question_title' => '章鱼身上长者几只触手？',
                'answer_list'    => [
                                        ['answer_key'=>'A','answer_val'=>'6只'],
                                        ['answer_key'=>'B','answer_val'=>'7只'],
                                        ['answer_key'=>'C','answer_val'=>'8只'],
                                        ['answer_key'=>'D','answer_val'=>'10只']
                                    ],
                'answer'         => 'C',
                'analysis'       => '都说吃八爪鱼，八爪鱼，八爪鱼了。以后吃的时候数一下，别让人给骗了。'
            ]
            ,
            [
                'question_id'    => '36',
                'question_type'  => '1',
                'question_title' => '七言绝句《小池》的第一句是？',
                'answer_list'    => [
                                        ['answer_key'=>'A','answer_val'=>'毕竟西湖六月中'],
                                        ['answer_key'=>'B','answer_val'=>'泉眼无声惜细流'],
                                        ['answer_key'=>'C','answer_val'=>'小河流水哗啦啦'],
                                        ['answer_key'=>'D','answer_val'=>'泉水无生西细留']
                                    ],
                'answer'         => 'B',
                'analysis'       => '我已经无力吐槽了！多读书，少玩手机！'
            ]
            ,
            [
                'question_id'    => '37',
                'question_type'  => '1',
                'question_title' => '周杰伦歌曲《夜曲》中，歌词[为你弹奏肖邦的夜曲]的下一句是？',
                'answer_list'    => [
                                        ['answer_key'=>'A','answer_val'=>'而我为你隐姓埋名'],
                                        ['answer_key'=>'B','answer_val'=>'在纯黑的环境凋零'],
                                        ['answer_key'=>'C','answer_val'=>'纪念我死去的爱情'],
                                        ['answer_key'=>'D','answer_val'=>'手在键盘敲很轻']
                                    ],
                'answer'         => 'C',
                'analysis'       => '天王巨星周杰伦的歌都没听过啊？'
            ]
            ,
            [
                'question_id'    => '38',
                'question_type'  => '1',
                'question_title' => '黄梅戏戏曲传统经典剧目《天仙配》剧中的男主角是？',
                'answer_list'    => [
                                        ['answer_key'=>'A','answer_val'=>'牛郎'],
                                        ['answer_key'=>'B','answer_val'=>'陈世美'],
                                        ['answer_key'=>'C','answer_val'=>'杨乃武'],
                                        ['answer_key'=>'D','answer_val'=>'董永']
                                    ],
                'answer'         => 'D',
                'analysis'       => '这要是不知道，不管是男的女的，都是钢铁直男直女了。'
            ]
            ,
            [
                'question_id'    => '39',
                'question_type'  => '1',
                'question_title' => '在天文雪上，黄道共多少个星座？',
                'answer_list'    => [
                                        ['answer_key'=>'A','answer_val'=>'十一个'],
                                        ['answer_key'=>'B','answer_val'=>'八十八个'],
                                        ['answer_key'=>'C','answer_val'=>'十三个'],
                                        ['answer_key'=>'D','answer_val'=>'十四个']
                                    ],
                'answer'         => 'C',
                'analysis'       => '我其实也是小时候看圣斗士星矢才了解到的，不然我也不知道！哈哈哈'
            ]
            ,
            [
                'question_id'    => '40',
                'question_type'  => '1',
                'question_title' => '太阳系八大行星中体积最大的是？',
                'answer_list'    => [
                                        ['answer_key'=>'A','answer_val'=>'土星'],
                                        ['answer_key'=>'B','answer_val'=>'天王星'],
                                        ['answer_key'=>'C','answer_val'=>'木星'],
                                        ['answer_key'=>'D','answer_val'=>'海王星']
                                    ],
                'answer'         => 'C',
                'analysis'       => '这些都是常识啊！不要只看名字。'
            ]
            ,
            [
                'question_id'    => '41',
                'question_type'  => '1',
                'question_title' => '拿破仑是哪一个国家的历史人物？',
                'answer_list'    => [
                                        ['answer_key'=>'A','answer_val'=>'美国'],
                                        ['answer_key'=>'B','answer_val'=>'英国'],
                                        ['answer_key'=>'C','answer_val'=>'法国'],
                                        ['answer_key'=>'D','answer_val'=>'德国']
                                    ],
                'answer'         => 'C',
                'analysis'       => '法国自从出了个拿破仑，就再没出过人才了。'
            ]
            ,
            [
                'question_id'    => '42',
                'question_type'  => '1',
                'question_title' => '中国第一水乡周庄位于我国哪个城市？',
                'answer_list'    => [
                                        ['answer_key'=>'A','answer_val'=>'泉州'],
                                        ['answer_key'=>'B','answer_val'=>'徐州'],
                                        ['answer_key'=>'C','answer_val'=>'苏州'],
                                        ['answer_key'=>'D','answer_val'=>'杭州']
                                    ],
                'answer'         => 'C',
                'analysis'       => '这..那个...额。大概是因为人家水比较多吧'
            ]
            ,
            [
                'question_id'    => '43',
                'question_type'  => '1',
                'question_title' => '在电影《指环王》系列中，魔戒最后在什么地方被摧毁？',
                'answer_list'    => [
                                        ['answer_key'=>'A','answer_val'=>'瑞文戴尔'],
                                        ['answer_key'=>'B','answer_val'=>'末日火山'],
                                        ['answer_key'=>'C','answer_val'=>'夏尔'],
                                        ['answer_key'=>'D','answer_val'=>'刚铎']
                                    ],
                'answer'         => 'B',
                'analysis'       => '肯定是火山啊！看过的都知道'
            ]
            ,
            [
                'question_id'    => '44',
                'question_type'  => '1',
                'question_title' => '下列书法家哪位是[欧体楷书]的创始人？',
                'answer_list'    => [
                                        ['answer_key'=>'A','answer_val'=>'欧阳修'],
                                        ['answer_key'=>'B','answer_val'=>'欧阳询'],
                                        ['answer_key'=>'C','answer_val'=>'欧阳克'],
                                        ['answer_key'=>'D','answer_val'=>'欧阳楷']
                                    ],
                'answer'         => 'B',
                'analysis'       => '回去多查查这方面的资料。其实答不出来情有可原啊'
            ]
            ,
            [
                'question_id'    => '45',
                'question_type'  => '1',
                'question_title' => '以下不属于孔子观点的是？',
                'answer_list'    => [
                                        ['answer_key'=>'A','answer_val'=>'仁者爱人'],
                                        ['answer_key'=>'B','answer_val'=>'因才施教'],
                                        ['answer_key'=>'C','answer_val'=>'克己复礼'],
                                        ['answer_key'=>'D','answer_val'=>'以德服人']
                                    ],
                'answer'         => 'D',
                'analysis'       => '额！其实我也不清楚。情有可原情有可原。'
            ]
            ,
            [
                'question_id'    => '46',
                'question_type'  => '1',
                'question_title' => '他知道我知道你知道他不知道吗？这句话里到底是谁不知道呢？',
                'answer_list'    => [
                                        ['answer_key'=>'A','answer_val'=>'他'],
                                        ['answer_key'=>'B','answer_val'=>'我'],
                                        ['answer_key'=>'C','answer_val'=>'你'],
                                        ['answer_key'=>'D','answer_val'=>'都不造']
                                    ],
                'answer'         => 'A',
                'analysis'       => '是不是没答出来，我们全公司上下，大家一起想了半天，才出的结果。'
            ]
            ,
            [
                'question_id'    => '47',
                'question_type'  => '1',
                'question_title' => '西方圣诞节中，圣诞老人是从那个地方进入到房间给孩子们送礼物？',
                'answer_list'    => [
                                        ['answer_key'=>'A','answer_val'=>'烟囱'],
                                        ['answer_key'=>'B','answer_val'=>'窗户'],
                                        ['answer_key'=>'C','answer_val'=>'大门'],
                                        ['answer_key'=>'D','answer_val'=>'地下室']
                                    ],
                'answer'         => 'A',
                'analysis'       => '不走烟囱走哪里，你家窗户大门不上锁的啊。至于地下室，打地洞啊。'
            ]
            ,
            [
                'question_id'    => '48',
                'question_type'  => '1',
                'question_title' => '生物学中[条件反射]理论的建构者是？',
                'answer_list'    => [
                                        ['answer_key'=>'A','answer_val'=>'巴普洛夫'],
                                        ['answer_key'=>'B','answer_val'=>'弗洛伊德'],
                                        ['answer_key'=>'C','answer_val'=>'约翰·华生'],
                                        ['answer_key'=>'D','answer_val'=>'马洛斯']
                                    ],
                'answer'         => 'A',
                'analysis'       => '额！这个有点难度的，我原谅你们了。'
            ]
            ,
            [
                'question_id'    => '49',
                'question_type'  => '1',
                'question_title' => '乌拉圭足球巨星苏亚雷斯在球场上有一项不太好的习惯，这个习惯是？',
                'answer_list'    => [
                                        ['answer_key'=>'A','answer_val'=>'踢人'],
                                        ['answer_key'=>'B','answer_val'=>'说垃圾话'],
                                        ['answer_key'=>'C','answer_val'=>'脱衣服'],
                                        ['answer_key'=>'D','answer_val'=>'咬人']
                                    ],
                'answer'         => 'D',
                'analysis'       => '看过这届世界杯的，都听过解说调蓄他，哈哈哈。'
            ]
            ,
            [
                'question_id'    => '50',
                'question_type'  => '1',
                'question_title' => '比喻心胸狭窄，像瞪眼睛那样极小的事也要报复的成语是？',
                'answer_list'    =>  [
                                        ['answer_key'=>'A','answer_val'=>'牝鸡司晨'],
                                        ['answer_key'=>'B','answer_val'=>'沆瀣一气'],
                                        ['answer_key'=>'C','answer_val'=>'桀骜不驯'],
                                        ['answer_key'=>'D','answer_val'=>'睚呲必报']
                                    ],
                'answer'         => 'D',
                'analysis'       => '您已通关，敬请期待后续版本！'
            ]
        ];

        var_dump(file_put_contents('../app/Libs/questions/question.txt', print_r(json_encode($data),true) ));die;
        /*if(is_file('../app/Libs/questions/question.txt')){
            echo '此文件存在';;die;
        }else{
            echo '此文件不存在';die;
        }*/
        $question['one'] = json_decode( file_get_contents('../app/Libs/questions/question.txt',true) );
        $question['level'] = '1';
        var_dump( $question  );die;
        if( file_get_contents('../app/Libs/questions/question.txt',true) ){
            echo '此文件存在';
        }else{
            echo '此文件不存在';
        }
    }

}
