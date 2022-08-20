<div class="wrap">

    <h1 class="wp-heading-inline">
        <?php echo  $chapter_id ? '编辑章节':'新增章节' ;?>
        
    </h1>

    <form action="" method="post" enctype="multipart/form-data">
        <div id="poststuff">

            <div id="post-body" class="metabox-holder columns-2">
                <div id="post-body-content" style="position: relative;">

                    <div id="titlediv">
                        <div id="titlewrap">
                            <input type="text" name="chapter_title" size="30" value="<?php echo $chapter_id ? $chapter->chapter_title:'' ?>" id="title" spellcheck="true" autocomplete="off">
                        </div>
                    </div>

                    <div style="margin-top: 20px;">
                        <?php wp_editor($chapter_id ? wpautop( $chapter->chapter_content ) : '', "chapter_content"); ?>
                    </div>

                    <div class="submit">
                        <input type="submit" name="save" id="publish" class="button button-primary button-large" value="保存章节">
                    </div>
                </div>

                <div id="postbox-container-1" class="postbox-container">
                    <table class="wp-list-table widefat fixed striped table-view-list posts">
                        <thead>
                            <tr>
                                <td>章节列表</td>
                                <!-- <td>排序</td> -->
                            </tr>  
                        </thead>
                        <tbody>
                            <?php foreach($chapters as $chapter){ ?>
                             <tr>
                                <td>
                                    <?php echo "<a href=\"edit.php?post_type=book&page=manage-chapters&post_id={$post_id}&chapter_id={$chapter->chapter_id}\">{$chapter->chapter_title}</a>"; 
                                    ?>    
                                </td>
                                <!-- <td><?php echo $chapter->chapter_order ?></td> -->
                            </tr>  
                            <?php } ?>
                        </tbody>
                    </table>
                    <div class="submit">
                        <a class="button button-primary button-large" href="edit.php?post_type=book&page=manage-chapters&post_id=<?php echo $post_id;?>" >新增章节</a>
                    </div>
                </div>
            </div>
        </div>

    </form>
</div>