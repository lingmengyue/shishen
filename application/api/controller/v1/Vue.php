<?php
/**
 * Created by PhpStorm.
 * User: 灵梦
 * Date: 2019/8/22
 * Time: 11:02
 */

namespace app\api\controller\v1;


use app\api\model\Comment;
use app\api\model\ImageCategory;
use app\api\model\ImageContent;
use app\api\model\ImageList;
use app\api\model\Newslist;
use think\Controller;
use app\api\model\Vue as VueModel;
use think\Db;
use think\Request;


class Vue extends Controller
{

    public function index(){
        return view();
    }
    public function getInfo(){
        $list = VueModel::all();
        return $list;
    }

    public function postInfo(){
        echo "post function";
    }

    public function jsonpInfo(){
        $list = [
            'test1' => 1,
            'test2' => 2,
            'test3' => 3,
        ];
        return jsonp($list);
    }

    public function insertInfo(){
        $requestData = Request::instance();
        $name = $requestData->param('name');
        $data = [
            'name' => $name,
            'ctime' => time(),
            'operation' => $name
        ];
        $vue = new VueModel();
        $vue->data($data);
        $vue->save();
        $id = $vue->id;
    }

    public function deleteInfo(){
        $requestData = Request::instance();
        $id = $requestData->param('id');
        VueModel::destroy($id);
        return "success";
    }

    public function getNewsList(){
        $newsData = Newslist::all();
        return json($newsData);
    }

    public function getNewsDetail($id){
        $newsData = Newslist::get($id);
        return json($newsData);
    }

    public function getComment($page,$limit){
        /*$commentData = Db::table('comment')->page($page,8)->select();*/
        $commentData = Comment::where('type_id',1)->paginate($limit);
        return $commentData;
    }

    public function saveComment(){
        $commentData = Request::instance();
        /*return $commentData;*/
        $data = [
            'userid' => $commentData->param('userid'),
            'username' => $commentData->param('username'),
            'comment' => $commentData->param('msg'),
            'type_id' => $commentData->param('type_id'),
            'show_id' => $commentData->param('show_id'),
        ];
        $comment = new Comment();
        $test = $comment->save($data);
        if($test){
            return json(['msg' => 'success']);
        }
        else{
            return json(['msg' => 'fail']);
        }
    }


    public function getImageCategory(){
        $categoryData = ImageCategory::all();
        return json(['msg' => 'success','data' => $categoryData]);
    }

    public function getImageCategoryContent($id){
        if($id == 0){
            $imageData = ImageContent::all();
        }
        else{
            $imageData = ImageContent::where('category_id',$id)->select();
        }

        return json(['msg' => 'success','data' => $imageData]);
    }

    public function getImageContent($id){
        $contentData = ImageContent::get($id);
        $imgsData = ImageList::all($contentData['imgs']);
        $contentData['images'] = $imgsData;
        return json(['msg' => 'success','data' => $contentData]);
    }
}