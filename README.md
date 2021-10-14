# Laravel-CRUD-Generator
## Description
Abstract Controller will **Generate** A complete Rest API CRUD  
## Usage
### Add the AbstractController.php file to the contollers path  
 ┣ 📂Controllers  
 ┃ ┗ 📜AbstractController.php    
 ┃ ┗ 📜ProductController.php  
 
 ### Extend your Controllers from The Abstract Controller
 #### For new contollers you need to provide the AbstractController constructer (3) parameters:   
 #### (Model, [StoreRequest, UpdateRequest] as FormRequest)
 ```php
class ProductController extends AbstractController
{
    public function __construct()
    {
        parent::__construct(Product::class,
            StoreProductRequest::class,
            UpdateProductRequest::class);
    }
}
```
