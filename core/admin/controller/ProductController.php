<?php


namespace core\admin\controller;


use core\user\model\Category;
use core\user\model\Product;

class ProductController extends BaseAdmin
{
    public function index(){

        $this->template = 'core/admin/view/product/index';
        $products = Product::instance()->getProduct();

        return ['products' => $products];
    }

    public function show(){

        $this->template = 'core/admin/view/product/show';
        $product = Product::instance()->getProduct($this->parameters['id']);

        return ['product' => $product[0]];
    }

    public function create(){

        $this->template = 'core/admin/view/product/form';
        $categories = Category::instance()->getCategories();

        if($this->isPost()){

            $this->clearPostFields();

            $this->createFiles('products');

            Product::instance()->createProduct($_POST, $this->messages, $this->fileArray['image']) ? $this->redirect(PATH . 'admin/products') : $this->redirect();
        }

        return ['categories' => $categories];
    }

    public function update(){
        $this->template = 'core/admin/view/product/form';
        $this->table = 'products';

        $product = Product::instance()->getProduct($this->parameters['id']);
        $categories = Category::instance()->getCategories();

        if($this->isPost()) {

            $this->clearPostFields();

            $this->createFiles('products', $_POST['id']);

            Product::instance()->updateProduct($_POST, $this->messages, $this->fileArray['image']) ?
                $this->redirect(PATH . 'admin/products') : $this->redirect();

        }

        return ['product' => $product[0], 'categories' => $categories];
    }

    public function delete(){

        Product::instance()->deleteProduct($this->parameters['id'], $this->messages);

        $this->redirect();

    }
}