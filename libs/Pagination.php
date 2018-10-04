<?php
class Pagination {
	public function paginate_function($item_per_page, $current_page, $total_records, $total_pages,$controllerName){
	    $pagination = '';
	    if($total_pages > 0 && $total_pages != 1 && $current_page <= $total_pages){
	        $pagination .= '<ul class="pagination pagination-sm float-right">';
	        $right_links    = $current_page + 3;
	        $previous       = $current_page - 1;
	        $next           = $current_page + 1; 
	        $first_link     = true; 
	        if($current_page > 1){
	            $previous_link = ($previous==0)?1:$previous;
	            $pagination .= '<li class="page-item first"><a class="page-link" href="'.URL.$controllerName.'/?page=1&max='.DS_PAGE_MAX_RESULT.'" data-page="1" title="First">&laquo;</a></li>'; 
	            $pagination .= '<li class="page-item"><a class="page-link" href="'.URL.$controllerName.'/?page='.$previous_link.'&max='.DS_PAGE_MAX_RESULT.'" data-page="'.$previous_link.'" title="Previous">&lt;</a></li>';
	                for($i = ($current_page-2); $i < $current_page; $i++){ 
	                    if($i > 0){
	                        $pagination .= '<li class="page-item"><a class="page-link" href="'.URL.$controllerName.'/?page='.$i.'&max='.DS_PAGE_MAX_RESULT.'" data-page="'.$i.'" title="Page'.$i.'">'.$i.'</a></li>';
	                    }
	                }   
	            $first_link = false; 
	        }
	        if($first_link){ 
	            $pagination .= '<li class="page-item first active"><a class="page-link" href="#">'.$current_page.'</a></li>';
	        }elseif($current_page == $total_pages){ 
	            $pagination .= '<li class="page-item last active"><a class="page-link" href="#">'.$current_page.'</a></li>';
	        }else{ 
	            $pagination .= '<li class="page-item active"><a class="page-link" href="#">'.$current_page.'</a></li>';
	        }      
	        for($i = $current_page+1; $i < $right_links ; $i++){ 
	            if($i<=$total_pages){
	                $pagination .= '<li class="page-item"><a class="page-link" href="'.URL.$controllerName.'/?page='.$i.'&max='.DS_PAGE_MAX_RESULT.'" data-page="'.$i.'" title="Page '.$i.'">'.$i.'</a></li>';
	            }
	        }
	        if($current_page < $total_pages){ 
	                $next_link = ($i > $total_pages)? $total_pages : $i;
	                $pagination .= '<li class="page-item"><a class="page-link" href="'.URL.$controllerName.'/?page='.$next_link.'&max='.DS_PAGE_MAX_RESULT.'" data-page="'.$next_link.'" title="Next">&gt;</a></li>';
	                $pagination .= '<li class="page-item last"><a class="page-link" href="'.URL.$controllerName.'/?page='.$total_pages.'&max='.DS_PAGE_MAX_RESULT.'" data-page="'.$total_pages.'" title="Last">&raquo;</a></li>'; 
	        }
	        $pagination .= '</ul>'; 
	    }
	return $pagination;
  	}
}
?>