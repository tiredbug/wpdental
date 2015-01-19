<?php

function tindakan_theme_comment($comment, $args, $depth) {
   $GLOBALS['comment'] = $comment; ?>
        <tbody>
          <tr>
            <td><?php echo get_comment_date(); ?> <?php edit_comment_link(__('(E)'),'  ','') ?></td>
            <td><?php comment_text() ?></td>
            <td><?php echo get_comment_meta( $comment->comment_ID, '_diagnosa', true ); ?></td>
            <td><?php echo get_comment_meta( $comment->comment_ID, '_terapi', true ); ?></td>
          </tr>
        </tbody>


<?php
        }
 
// Do not delete this section
if (isset($_SERVER['SCRIPT_FILENAME']) && 'comments.php' == basename($_SERVER['SCRIPT_FILENAME'])){
  die ('Please do not load this page directly. Thanks!'); }
if ( post_password_required() ) { ?>
  <div class="alert alert-warning">
    <?php _e('This post is password protected. Enter the password to view comments.', 'theme'); ?>
  </div>
<?php
  return; 
}
// End do not delete section


 
?>

<ul class="pagination">
  <li class="older"><?php previous_comments_link() ?></li>
  <li class="newer"><?php next_comments_link() ?></li>
</ul>

      <div class="row">
        <div class="col-xs-12">
          <div class="panel panel-default">
                  <div class="panel-heading text-center">
                    <h4>Tindakan (Jumlah kunjungan: <?php comments_number('0', '1', '%'); ?> kali)</h4>
                  </div>
  
<div class="table-responsive">
      <table class="table">
        <thead>
          <tr>
            <th><h4>Tanggal</h4></th>
            <th><h4>Anamnesa</h4></th>
            <th><h4>Diagnosa</h4></th>
            <th><h4>Terapi</h4></th>
          </tr>
        </thead>

  <?php wp_list_comments('type=comment&callback=tindakan_theme_comment');?>




<?php if (comments_open()) : ?>
  <form action="<?php echo get_option('siteurl'); ?>/wp-comments-post.php" method="post" id="commentform">
    <?php if (is_user_logged_in()) : ?>
        <tbody>
          <tr>
            <td><?php echo date('j F Y'); ?></td>
            <td><textarea name="comment" class="form-control" id="comment" placeholder="Anamnesa dan Pemeriksaan Klinis" rows="3" aria-required="true"></textarea></td>
            <td><textarea name="_diagnosa" class="form-control" id="diagnosa" placeholder="Diagnosa" rows="3"></textarea></td>
            <td><textarea name="_terapi" class="form-control" id="terapi" placeholder="Terapi" rows="3"></textarea></td>
          </tr>
        </tbody>

        <tbody>
          <tr>
            <td></td>
            <td></td>
            <td></td>
             <td class="text-right"><input name="submit" class="btn btn-default" type="submit" id="submit" value="Input Tindakan"></td>

          </tr>
        </tbody>

    <?php else : ?>

    <?php endif; ?>


    <?php comment_id_fields(); ?>
    <?php do_action('comment_form', $post->ID); ?>
  </form>
<?php endif; ?>

      </table>
</div>
          </div>
        </div>
      </div>


