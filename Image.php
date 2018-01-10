<?php
class Image {
    //图片资源
    private $img;
    //图片路径
    private $src;
    //图片类型
    private $type;
    //方法名称imagePng
    private $output;

    /**
     * Image constructor.
     * @param $src [将要操作的图片的路径]
     */
    public function __construct( $src ) {
        $this->src = $src;
        //获取后缀
        $this->type = $this->getType( $src );
        $this->output = 'image' . $this->type;
    }

    /**
     * 获取图片类型后缀
     * @param $src [图片路径]
     * @return bool|string [图片类型后缀]
     */
    private function getType( $src ) {
        return image_type_to_extension( getimagesize( $src ) [2], false );
    }

    /**
     * 获取方法名
     * @param $type [图片类型后缀]
     * @return string [方法名]
     */
    private function getFun( $type ) {
        return 'imagecreatefrom' . $type;
    }

    /**
     * 若当前图片资源未获取 则获取资源 否则不执行任何操作
     */
    private function getImg() {
        if( ! isset( $this->img ) ) {
            $this->img = ( $this->getFun( $this->type ) )( $this->src );
        }
    }

    /**
     * 添加水印
     * @param $src [水印logo图片路径]
     * @param int $bottom [logo距离原图右边的距离]
     * @param int $right [logo距离原图下边的距离]
     * @param float $opacity [logo透明度]
     */
    public function mark( $src, $bottom = 0, $right = 0, $opacity = .3 ) {
        //获取图片资源
        $this->getImg();
        $srcImg = ( $this->getFun( $this->getType($src) ) )( $src );
        //获取水印logo宽高
        $srcWidth = imagesX( $srcImg );
        $srcHeight = imagesY( $srcImg );
        //计算水印位置
        $dstX = imagesX( $this->img ) - $srcWidth - $bottom;
        $dstY = imagesY( $this->img ) - $srcHeight - $right;
        //合并水印
        imagecopymerge( $this->img, $srcImg, $dstX, $dstY, 0, 0, $srcWidth, $srcHeight, $opacity * 100 );
        imagedestroy( $srcImg );
    }

    /**
     * @param $width [压缩后的宽度]
     * @param $height [压缩后的高度]
     */
    public function resize( $width , $height ) {
        $dstImg = imagecreatetruecolor( $width, $height );
        $this->getImg();
        $srcW = imagesX( $this->img );
        $srcH = imagesY( $this->img );
        imagecopyresized( $dstImg, $this->img, 0, 0, 0, 0, $width, $height, $srcW, $srcH );
        $this->img = $dstImg;
    }

    /**
     * 显示图片
     * @param null $path [图片保存路径]
     */
    public function show( $path = null ) {
        if( is_null($path) ){
            header('Content-type:image/' . $this->type);
            ($this->output)( $this->img );
        }else{
            $this->save( $path );
        }
    }

    /**
     * 保存图片
     * @param null $path (不传参则默认保存至default目录下)
     * @return null|string [图片保存后的完整路径]
     */
    public function save( $path = null ) {
        if( is_null($path) ) {
            $path =  __DIR__ . '/default/' . uniqid() . '.' .  $this->type;
        }
        ($this->output)( $this->img , $path );
        return $path;
    }

    public function __destruct() {
        imagedestroy( $this->img );
    }
}
//$img = new Image('./images/taylor.jpg');

//$img->show();
//echo $img->save();
//$img->save( './images/success.png' );