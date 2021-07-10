<?php

/**
 * Provide a public-facing view for the plugin
 *
 * This file is used to markup the public-facing aspects of the plugin.
 *
 * @link       https://www.fiverr.com/junaidzx90
 * @since      1.0.0
 *
 * @package    Progress
 * @subpackage Progress/public/partials
 */
?>

<!-- This file should primarily consist of HTML with a little bit of PHP. -->
<div class="progress_entry">
    <div class="p__entry">
        <?php
        foreach($results as $result){
            $number = $result->number;
            if($result->min !== '0' && $result->max !== '0'){
                $number = rand($result->min,$result->max);
            }
            echo '<span style="font-size: '.$result->fontsize.'px; color:'.$result->textcolor.';" class="lslot">'.ucfirst($result->leftslot).'</span>';
            echo '<span style="font-size: '.$result->fontsize.'px; color: '.$result->numbercolor.'" class="mnumber">'.$number.'</span>';
            echo '<span style="font-size: '.$result->fontsize.'px; color:'.$result->textcolor.';" class="rslot">'.ucfirst($result->rightslot).'</span>';
        }
        ?>
    </div>
</div>