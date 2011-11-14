<h4>Google Docs Zend Gdata Test Application</h4>
<?php
    $PagingdData = $this->Paging->page($array, 1);
    
    echo $this->Paging->first("<< First");
    echo "&nbsp;&nbsp;&nbsp;&nbsp;";
    echo $this->Paging->back("< Previous");
    echo "&nbsp;&nbsp;&nbsp;&nbsp;";
    echo $this->Paging->pagedLinks(2);
    echo "&nbsp;&nbsp;&nbsp;&nbsp;";
    echo $this->Paging->next("Next >");
    echo "&nbsp;&nbsp;&nbsp;&nbsp;";
    echo $this->Paging->last("Last >>");
    
    echo "<br /><br />";
    echo "hasNext: " . $this->Paging->hasNext(). "<br />";
    echo "hasPrev: " . $this->Paging->hasPrev(). "<br />";
    foreach($PagingdData as $row) {
        echo "<h4>".$row."</h4>";
    }
    echo "<br /><br />";
    
    echo $this->Paging->first("<< First");
    echo "&nbsp;&nbsp;&nbsp;&nbsp;";
    echo $this->Paging->back("< Previous");
    echo "&nbsp;&nbsp;&nbsp;&nbsp;";
    echo $this->Paging->pagedLinks(2);
    echo "&nbsp;&nbsp;&nbsp;&nbsp;";
    echo $this->Paging->next("Next >");
    echo "&nbsp;&nbsp;&nbsp;&nbsp;";
    echo $this->Paging->last("Last >>");
?>