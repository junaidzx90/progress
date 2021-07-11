<?php

/**
 * Provide a admin area view for the plugin
 *
 * This file is used to markup the admin-facing aspects of the plugin.
 *
 * @link       https://www.fiverr.com/junaidzx90
 * @since      1.0.0
 *
 * @package    Progress
 * @subpackage Progress/admin/partials
 */
?>
<h3>Progress entries</h3>
<hr>
<!-- This file should primarily consist of HTML with a little bit of PHP. -->
<div id="progress_wrap">

    <div id="add_entry_wrap">
        <button :disabled="isDisabled" @click="popupshow" id="add_entry" class="button-secondary">Add Entry</button>

        <div v-show="popup" id="progress_popup">
            <h4>New entry<span @click="closepopup" class="close_popup">+</span></h4>
            <span v-if="warning" class="warning">{{warning}}</span>
            <div class="progress_contents">
                <div class="inputs">
                    <input v-model="entryName" class="title" type="text" placeholder="Entry name">
                </div>
                <div class="checkbox-inputs">
                    <input class="switch" @change="borderswitched" type="checkbox">
                </div>
                <div class="inputs">
                    <label for="edit_">Text
                        <input v-model="textcolor" type="color" id="textcolor">
                    </label>
                    <label for="numbercolor">Number
                        <input v-model="numbercolor" type="color" id="numbercolor">
                    </label>
                    <label v-if="borderswitch" for="bordercolor">Border
                        <input v-model="bordercolor" type="color" id="bordercolor">
                    </label>
                    <label for="fontsize">Font size
                        <input v-model="fontsize" type="number" id="fontsize">
                    </label>
                </div>
                <div class="inputs">
                    <input v-model="leftslot" type="text" placeholder="Left slot">

                    <select @change="current_type(event)" id="types" class="type">
                        <option value="single">Single</option>
                        <option value="random">Random</option>
                        <option value="countdown">Countdown</option>
                    </select>
                </div>
                
                <div v-if="numberInp" class="inputs single_inp">
                    <input v-model="single" type="number" placeholder="Number">
                </div>

                <div v-if="randomInp" class="random">
                    <input v-model="min" type="number" placeholder="Min">&nbsp;
                    <input v-model="max" type="number" placeholder="Max">
                </div>

                <div v-if="countdownInp" class="countdown">
                    <input v-model="min" type="number" placeholder="Min">&nbsp;
                    <input v-model="max" type="number" placeholder="Max">&nbsp;
                    <input v-model="seconds" type="number" placeholder="Seconds">
                </div>
                
                <div class="inputs">
                    <input v-model="rightslot" type="text" placeholder="Right slot">

                    <button :disabled="isDisabled" @click="addEntry" class="button-secondary">Add</button>
                </div>
            </div>
        </div>
    </div>

    <div class="entries_data">
        <table id="entries_table">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Entry Name</th>
                    <th>Shortcode</th>
                    <th>Left Slot</th>
                    <th>Right Slot</th>
                    <th>Number</th>
                    <th>Min</th>
                    <th>Max</th>
                    <th>Date</th>
                    <th>#</th>
                </tr>
            </thead>
            <tbody>
                <?php
                global $wpdb;

                $results = $wpdb->get_results("SELECT * FROM {$wpdb->prefix}progress_entries_v2 ORDER BY ID DESC");
                if($results){
                    $i = 1;
                    foreach($results as $result){
                        ?>
                        <tr>
                            <td><?php echo $i ?></td>
                            <td><?php echo $result->entryname ?></td>
                            <td><input type="text" disabled value='[progress entry="<?php echo $result->ID ?>"]'></td>
                            <td><?php echo $result->leftslot ?></td>
                            <td><?php echo $result->rightslot ?></td>
                            <td><?php echo $result->number ?></td>
                            <td><?php echo $result->min ?></td>
                            <td><?php echo $result->max ?></td>
                            <td><?php echo $result->create_date ?></td>
                            <td>
                                <button @click="typeselect(<?php echo $result->ID ?>)" class="edit-entry button-secondary">Edit</button>
                                <button @click="delete_entry(<?php echo $result->ID ?>)" class="del-entry button-secondary">Delete</button>

                                <div class="edit_popup">
                                    <h4>Edit entry<span @click="closeeditpopup" class="close_popup closeedit">+</span></h4>
                                    <span v-if="warning" class="warning">{{warning}}</span>
                                    <div class="progress_contents">
                                        <div class="inputs">
                                            <input class="title edit_entryname<?php echo $result->ID ?>" type="text" value="<?php echo $result->entryname; ?>" placeholder="Entry name">
                                        </div>
                                        <div class="checkbox-inputs">
                                            <?php
                                            $switch = '';
                                            if(intval($result->border_switch) == 1){
                                                $switch = 'checked';
                                            }else{
                                                $switch = '';
                                            }
                                            ?>
                                            <input <?php echo $switch ?> class="switch borderswitch bswitch<?php echo $result->ID ?>" type="checkbox">
                                        </div>
                                        <div class="inputs">
                                            <label for="textcolor">Text
                                                <input value="<?php echo $result->textcolor; ?>" type="color" class="edit_textcolor<?php echo $result->ID ?>">
                                            </label>
                                            <label for="numbercolor">Number
                                                <input value="<?php echo $result->numbercolor; ?>" type="color" class="edit_numbercolor<?php echo $result->ID ?>">
                                            </label>
                                            <?php
                                            $show = '';
                                            if(intval($result->border_switch) == 1){
                                                $show = 'style="display:block;"';
                                            }else{
                                                $show = 'style="display:none;"';
                                            }
                                            ?>
                                            <label <?php echo $show; ?> for="bordercolor">Border
                                                <input value="<?php echo $result->bordercolor; ?>" type="color" class="edit_bordercolor<?php echo $result->ID ?>">
                                            </label>
                                            <label for="fontsize">Font size
                                                <input value="<?php echo $result->fontsize; ?>" type="number" class="edit_fontsize<?php echo $result->ID ?>">
                                            </label>
                                        </div>
                                        <div class="inputs">
                                            <input class="edit_left<?php echo $result->ID ?>" type="text" value="<?php echo $result->leftslot ?>" placeholder="Left slot">

                                            <select @change="edit_current_type(event)" class="type edittypes edittype<?php echo $result->ID ?>">
                                                <option <?php echo ($result->number > 0)?'selected':'' ?> value="single">Single</option>
                                                <option <?php echo ($result->min > 0 && $result->max > 0 && $result->seconds == 0)?'selected':'' ?> value="random">Random</option>
                                                <option <?php echo ($result->min > 0 && $result->max > 0 && $result->seconds > 0 )?'selected':'' ?> value="edit_countdown">Countdown</option>
                                            </select>
                                        </div>
                                        
                                        <div v-if="edit_numberInp" class="inputs single_inp">
                                            <input class="edit_number<?php echo $result->ID ?>" type="number" value="<?php echo $result->number ?>" placeholder="Number">
                                        </div>
                                        
                                        <div v-if="edit_randomInp" class="random">
                                            <input class="edit_min<?php echo $result->ID ?>" type="number" value="<?php echo $result->min ?>" placeholder="Min">&nbsp;
                                            <input class="edit_max<?php echo $result->ID ?>" type="number" value="<?php echo $result->max ?>" placeholder="Max">
                                        </div>

                                        <div v-if="edit_countdown" class="edit_countdown">
                                            <input class="edit_min<?php echo $result->ID ?>" type="number" value="<?php echo $result->min ?>" placeholder="Min">&nbsp;
                                            <input class="edit_max<?php echo $result->ID ?>" type="number" value="<?php echo $result->max ?>" placeholder="Max">&nbsp;
                                            <input class="edit_seconds<?php echo $result->ID ?>" type="number" value="<?php echo $result->seconds; ?>" placeholder="Seconds">
                                        </div>
                                        
                                        <div class="inputs">
                                            <input class="edit_right<?php echo $result->ID ?>" type="text" value="<?php echo $result->rightslot ?>" placeholder="Right slot">

                                            <button @click="update_entry(<?php echo $result->ID ?>)"  class="editentrysave button-secondary">Update</button>
                                        </div>
                                    </div>
                                </div>
                            </td>
                        </tr>
                        <?php
                        $i++;
                    }
                }
                ?>
            </tbody>
        </table>
    </div>
</div>