<?php namespace App\Commands;
class CategoryHelper {

    private $categories;

    public function __construct($categories) {
      $this->categories = $categories;
    }

    public function htmlSelectOptions($selected = 0) {
      return $this->htmlFromArray($this->categoryArray(), $selected);
    }

    private function categoryArray() {
      $result = array();
      foreach($this->categories as $category) {
        if ($category->parent_id == 0) {
          $result[$category->id] = $this->categoryWithChildren($category);
        }
      }
      return $result;
    }

    private function childrenOf($category) {
      $result = array();
      foreach($this->categories as $i) {
        if ($i->parent_id == $category->id) {
          $result[] = $i;
        }
      }
      return $result;
    }

    private function categoryWithChildren($category) {
      $result = array();
      $children = $this->childrenOf($category);
      foreach ($children as $child) {
        $result[$child->id] = $this->categoryWithChildren($child);
      }
      return $result;
    }

    private function htmlFromArray($array, $id) {
      $html = "";
      foreach ($array as $key => $value) {
        $tmpCat = $category = \App\Category::find(intval($key));
        if($key === $id){
          $html .= "<option value='".$key."' selected>";
        }
        else{
          $html .= "<option value='".$key."'>";
        }
        while(!is_null($tmpCat->parent_id)){
          $html .= "&nbsp;&nbsp;&nbsp;";
          $tmpCat = $tmpCat->parent;
        }
        $html .= $category->name."</option>";
        if(count($value)>0){
          $html .= $this->htmlFromArray($value, $id);
        }
      }
      return $html;
    }
  }
