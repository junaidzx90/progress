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
<?php 
$style = $wpdb->get_row("SELECT bordercolor,border_switch FROM {$wpdb->prefix}progress_entries_v2 WHERE ID = $entry_id");
$border_switch = '';
if($style->border_switch == '1'){
    $border_switch = 'border-top: 1px solid '.$style->bordercolor.'!important; border-bottom: 1px solid '.$style->bordercolor.'!important';
}else{
    $border_switch = 'border: none !important';
}
?>
<!-- This file should primarily consist of HTML with a little bit of PHP. -->
<div class="progress_entry" style="<?php echo $border_switch; ?>">
    <div class="p__entry">
        <?php
        foreach($results as $result){
            $number = $result->number;
            if($result->min !== '0' && $result->max !== '0'){
                $number = rand($result->min,$result->max);
            }
            echo '<span style="font-size: '.$result->fontsize.'px; color:'.$result->textcolor.';" class="lslot">'.ucfirst($result->leftslot).'</span>';
            echo '<span style="font-size: '.$result->fontsize.'px; color: '.$result->numbercolor.'" class="mnumber" id="ncdn'.$entry_id.'">'.$number.'</span>';
            echo '<span style="font-size: '.$result->fontsize.'px; color:'.$result->textcolor.';" class="rslot">'.ucfirst($result->rightslot).'</span>';
            ?>
            <script>
                <?php

                $oparator = '-=';
                if($result->countup > 0){
                    $oparator = '+=';
                }

                if($result->min !== '0' && $result->max !== '0' && $result->seconds !== '0'){ ?>
                    let nmcounter<?php echo $entry_id ?> = document.getElementById('ncdn<?php echo $entry_id ?>');
                    let current_val<?php echo $entry_id ?> = parseInt(nmcounter<?php echo $entry_id ?>.textContent);
                    var decrementCounter<?php echo $entry_id ?> = setInterval(function(){
                        current_val<?php echo $entry_id ?> <?php echo $oparator ?> 1;
                        nmcounter<?php echo $entry_id ?>.innerText = current_val<?php echo $entry_id ?>;
                        if(current_val<?php echo $entry_id ?> == 1){
                            clearInterval(decrementCounter<?php echo $entry_id ?>);
                        }
                    }, <?php echo intval($result->seconds*1000) ?>);
                    <?php
                }
                ?>
            </script>
            <?php
        }
        ?>
    </div>
</div>