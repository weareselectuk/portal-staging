<?php

$args = array(
    'post_type' => 'staff',
'posts_per_page' => 999,
'orderby' => 'firstname',
'order' => 'ASC',
    'meta_query' => array(
        array(
            'key'     => 'client',
            'value'   => get_the_id(),
    'compare' => '=',
        ),
    )
);

$the_query = new WP_Query( $args );

if ($the_query->have_posts()) : ?>

<div class="filter-container">
  <div class="filter-column">
  <div class="box-title" style="color:#999;width:200px">
              <span style="font-weight:bold;font-size:14px;color:#932082; margin-right:5px;">I</span><span style="font-weight:medium;font-size:14px;color:#2A317E;">USER STATUS</span><i class="fas fa-question-circle" href="#" data-toggle="tooltip" title="Tooltip Text Goes Here" style="color:#932082; margin-left:10px;"></i>
                  </div>
    <div class="filter-box">
      <div class="filter-count"><?php echo $the_query->found_posts; ?></div>
      <button class="filter-title btn-filter users" data-target="all">TOTAL</button>
</div>
      <div class="filter-box">
      <div class="filter-count-green"><?php
$args        = array(
    'post_type' => 'staff',
    'posts_per_page' => 9999,
    'orderby' => 'title',
    'order' => 'ASC',
    'meta_query' => array(
        array(
            'key' => 'client',
            'value' => get_the_id(),
            'compare' => '='
        ),
        array(
            'key' => 'user_status',
            'value' => 'Active',
            'compare' => '='
        )
    )
);
$inner_query = new WP_Query($args);
?><?php
if ($inner_query->have_posts()):
    echo $inner_query->found_posts;
?>  <?php
else:
    echo '0';
?> <?php
endif;
?> </div>
      <button class="filter-title-green btn-filter users" data-target="Active">ACTIVE</button>
</div>
      <div class="filter-box">
      <div class="filter-count-red"><?php
$args        = array(
    'post_type' => 'staff',
    'posts_per_page' => 9999,
    'orderby' => 'title',
    'order' => 'ASC',
    'meta_query' => array(
        array(
            'key' => 'client',
            'value' => get_the_id(),
            'compare' => '='
        ),
        array(
            'key' => 'user_status',
            'value' => 'Not Active',
            'compare' => '='
        )
    )
);
$inner_query = new WP_Query($args);
?><?php
if ($inner_query->have_posts()):
    echo $inner_query->found_posts;
?>  <?php
else:
    echo '0';
?> <?php
endif;
?> </div>
      <button class="filter-title-red btn-filter users" data-target="Not Active">NOT ACTIVE</button>
</div>
</div>
<div class="filter-column" style="margin-left:83px;">
  <div class="box-title" style="color:#999;width:200px;">
              <span style="font-weight:bold;font-size:14px;color:#932082; margin-right:5px;">I</span><span style="font-weight:medium;font-size:14px;color:#2A317E;">EMPLOYMENT STATUS</span><i class="fas fa-question-circle" href="#" data-toggle="tooltip" title="Tooltip Text Goes Here" style="color:#932082; margin-left:10px;"></i>
                  </div>
<div class="filter-box">
      <div class="filter-count-blue"><?php
$args        = array(
    'post_type' => 'staff',
    'posts_per_page' => 9999,
    'orderby' => 'title',
    'order' => 'ASC',
    'meta_query' => array(
        array(
            'key' => 'client',
            'value' => get_the_id(),
            'compare' => '='
        ),
        array(
            'key' => 'user_employment',
            'value' => 'Full-Time',
            'compare' => '='
        )
    )
);
$inner_query = new WP_Query($args);
?><?php
if ($inner_query->have_posts()):
    echo $inner_query->found_posts;
?>  <?php
else:
    echo '0';
?> <?php
endif;
?> </div>
      <button class="filter-title-blue btn-filter users" data-target="Full-Time">FULL-TIME</button>
</div>
      <div class="filter-box">
      <div class="filter-count-blue"><?php
$args        = array(
    'post_type' => 'staff',
    'posts_per_page' => 9999,
    'orderby' => 'title',
    'order' => 'ASC',
    'meta_query' => array(
        array(
            'key' => 'client',
            'value' => get_the_id(),
            'compare' => '='
        ),
        array(
            'key' => 'user_employment',
            'value' => 'Part-Time',
            'compare' => '='
        )
    )
);
$inner_query = new WP_Query($args);
?><?php
if ($inner_query->have_posts()):
    echo $inner_query->found_posts;
?>  <?php
else:
    echo '0';
?> <?php
endif;
?> </div>
      <button class="filter-title-blue btn-filter users" data-target="Part-Time">PART-TIME</button>
</div>
      <div class="filter-box">
      <div class="filter-count-blue"><?php
$args        = array(
    'post_type' => 'staff',
    'posts_per_page' => 9999,
    'orderby' => 'title',
    'order' => 'ASC',
    'meta_query' => array(
        array(
            'key' => 'client',
            'value' => get_the_id(),
            'compare' => '='
        ),
        array(
            'key' => 'user_employment',
            'value' => 'Freelance',
            'compare' => '='
        )
    )
);
$inner_query = new WP_Query($args);
?><?php
if ($inner_query->have_posts()):
    echo $inner_query->found_posts;
?>  <?php
else:
    echo '0';
?> <?php
endif;
?> </div>
      <button class="filter-title-blue btn-filter users" data-target="Freelance">FREELANCE</button>
</div>
</div>
<button class="box-title btn-filter users" type="button" data-target="all" style="color:#999;float:right;border: none; background-color: #eeeded;">
<i style="color:#F1852E; margin-left:10px;" class="fas fa-power-off"></i><span style="font-weight:bold;font-size:14px;"> RESET</span>FILTERS
</button>
</div>
            <div class="table-responsive max-height" style="max-height: 475px;">
              <table class="table">
                <tr>
                  <th width="equal-width"><strong style="text-transform: uppercase; color: #99999F;  font-size: 17px;">Title</strong></th>
                  <th width="equal-width"><strong style="text-transform: uppercase; color: #99999F;  font-size: 17px;">First Name</strong></th>
                  <th width="equal-width"><strong style="text-transform: uppercase; color: #99999F;  font-size: 17px;">Last Name</strong></th>
                  <th width="equal-width"><strong style="text-transform: uppercase; color: #99999F;  font-size: 17px;">DDI Telephone</strong></th>
                  <th width="equal-width"><strong style="text-transform: uppercase; color: #99999F;  font-size: 17px;">Mobile</strong></th>
                  <th width="equal-width"><strong style="text-transform: uppercase; color: #99999F;  font-size: 17px;">Email</strong></th>
                  <th width="equal-width"><strong style="text-transform: uppercase; color: #99999F;  font-size: 17px;">Employment status</strong></th>
                  <th width="equal-width"><strong style="text-transform: uppercase; color: #99999F;  font-size: 17px;">FUNCTIONS</strong></th>
                  <th width="equal-width"></th>
                  
                 

                </tr>


                <?php while ( $the_query->have_posts() ) : $the_query->the_post(); ?>
                <?php $meta_key = 'user_status'; $id = get_the_id(); ?>
                <tr class="filterable users" data-status="<?php echo get_post_meta( $id, $meta_key, true ); $id = $meta_key = '';?>"
                <?php $meta_key = 'user_employment'; $id = get_the_id(); ?> data-employment="<?php echo get_post_meta( $id, $meta_key, true ); $id = $meta_key = '';?>">
                <td align="left">
                    <?php $meta_key = 'title'; $id = get_the_id();?>
                    <a data-title="Title" href="#" id="text-field" name="<?php echo $meta_key;?>" data-type="text" data-inputclass="form-control" data-pk="<?php echo $id;?>" class="text-field">
                      <?php echo get_post_meta( $id, $meta_key, true ); $id = $meta_key = '';?>
                    </a>
                  </td>
                  <td>
                    <?php $meta_key = 'firstname'; $id = get_the_id();?>
                    <a data-title="First Name" href="#" id="text-field" name="<?php echo $meta_key;?>" data-type="text" data-inputclass="form-control" data-pk="<?php echo $id;?>" class="text-field">
                      <?php echo get_post_meta( $id, $meta_key, true ); $id = $meta_key = '';?>
                    </a>
                  </td>
                  <td align="left">
                    <?php $meta_key = 'lastname'; $id = get_the_id();?>
                    <a data-title="Last Name" href="#" id="text-field" name="<?php echo $meta_key;?>" data-type="text" data-inputclass="form-control" data-pk="<?php echo $id;?>" class="text-field">
                      <?php echo get_post_meta( $id, $meta_key, true ); $id = $meta_key = '';?>
                    </a>
                  </td>
                  <td align="left">
                    <?php $meta_key = 'ddi_telephone'; $id = get_the_id();?>
                    <a data-title="DDI Telephone" href="#" id="text-field" name="<?php echo $meta_key;?>" data-type="text" data-inputclass="form-control" data-pk="<?php echo $id;?>" class="text-field">
                      <?php echo get_post_meta( $id, $meta_key, true ); $id = $meta_key = '';?>
                    </a>
                  </td>
                  <td align="left">
                    <?php $meta_key = 'mobile'; $id = get_the_id();?>
                    <a data-title="Mobile" href="#" id="text-field" name="<?php echo $meta_key;?>" data-type="text" data-inputclass="form-control" data-pk="<?php echo $id;?>" class="text-field">
                      <?php echo get_post_meta( $id, $meta_key, true ); $id = $meta_key = '';?>
                    </a>
                  </td>
                   <td align="left">
                    <?php $meta_key = 'email_address'; $id = get_the_id();?>
                    <a data-title="Email" href="#" id="text-field" name="<?php echo $meta_key;?>" data-type="text" data-inputclass="form-control" data-pk="<?php echo $id;?>" class="text-field">
                      <?php echo get_post_meta( $id, $meta_key, true ); $id = $meta_key = '';?>
                    </a>
                  </td>
                  <td align="left">
                  <?php $meta_key = 'user_employment'; $id = get_the_id(); ?><a data-title="Site Status" href="#" id="site-status"
                      name="<?php echo $meta_key;?>" data-type="select" data-value="" data-source="[{value:'Full-Time',text:'Full Time'},{value:'Part-Time',text:'Part Time'},{value:'Freelance',text:'Freelance'}]"
                      data-pk="<?php echo $id;?>" class="text-field">
                      <?php echo get_post_meta( $id, $meta_key, true ); $id = $meta_key = '';?></a>
                
                   
                    </a>
                  </td>
                  <!--Features-->
                     <td align="left"><a href="#userstatus_<?php $post_id = get_the_ID(); echo $post_id;?>" data-toggle="modal" data-target="#userstatus_<?php $post_id = get_the_ID(); echo $post_id;?>"> <span class="fa fa-check-circle fa-lg <?php if( empty( get_post_meta( $post_id, 'user_status', true ) ) ) : echo 'false'; endif; ?>" style="color:#50AE54" title="User Status"></span></a>
                     <a href="#employmenttype_<?php $post_id = get_the_ID(); echo $post_id;?>" data-toggle="modal" data-target="#employmenttype_<?php $post_id = get_the_ID(); echo $post_id;?>"><span class="fas fa-user-tie fa-lg <?php if( empty( get_post_meta( $post_id, 'day_mon', 'day_tues', 'day_wed', 'day_thurs', 'day_fri', true ) ) ) : echo 'false'; endif; ?>" title="Employment Type" ></span></a>
                     <a href="#userlogins_<?php $post_id = get_the_ID(); echo $post_id;?>" data-toggle="modal" data-target="#userlogins_<?php $post_id = get_the_ID(); echo $post_id;?>"><span class="fas fa-sign-in-alt fa-lg <?php if( empty( get_post_meta( $post_id, 'login_type', 'login_username', 'login_password', true ) ) ) : echo 'false'; endif; ?>" title="User Logins"></span></a>
                     <a href="#emailconfig_<?php $post_id = get_the_ID(); echo $post_id;?>" data-toggle="modal" data-target="#emailconfig_<?php $post_id = get_the_ID(); echo $post_id;?>"><span class="fas fa-envelope fa-lg <?php if( empty( get_post_meta( $post_id, 'email_address', 'password', 'license_type', true ) ) ) : echo 'false'; endif; ?>" title="Email Configuration"></span></a>
                    
                     
                    </td>
                     <td class="modalsloader"><?php
                     include get_template_directory() . '/users-modal.php'; ?></td> 
                </tr>


                <?php endwhile;

echo '</table></div>';

else :

echo '<p>This Client Has No Staff</p>';

endif;


wp_reset_postdata();?>
