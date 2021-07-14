var app = new Vue({
	el: '#progress_wrap',
	data: {
		isDisabled: false,
		warning: '',
		popup: false,
		borderswitch: false,
		types: '',
		edit_types: '',
		edit_numberInp: true,
		edit_randomInp: false,
		edit_countdown: false,
		numberInp: true,
		randomInp: false,
		countableInp: false,
		
		textcolor: '#666666',
		numbercolor: '#666666',
		bordercolor: '#666666',
		fontsize: '18',
		entryName: '',
		leftslot: '',
		single: 0,
		min: 0,
		max: 0,
		seconds: 30,
		rightslot: '',
		countup: 0,
		editcountup: 0,
		// table data
		tableData: []
	},
	methods: {
		popupshow: function () {
			this.popup = true;
		},
		closepopup: function () {
			this.popup = false;
		},
		closeeditpopup: function () {
			this.edit_borderswitch = false;
		},
		borderswitched: function () {
			if (this.borderswitch === true) {
				this.borderswitch = false;
			} else {
				this.borderswitch = true;
			}
		},
		current_type: function (event) {
			this.types = event.target.value
			if (this.types == 'countup') {
				this.numberInp 	= false;
				this.randomInp 	= false;
				this.countableInp 	= true;
				this.single 	= 0;
				this.min 		= 0;
				this.max 		= 0;
				this.seconds 	= 0;
				this.countup 	= 1;
			}
			if (this.types == 'countdown') {
				this.numberInp 	= false;
				this.randomInp 	= false;
				this.countableInp 	= true;
				this.single 	= 0;
				this.min 		= 0;
				this.max 		= 0;
				this.seconds 	= 0;
				this.countup 	= 0;
			}
			if (this.types == 'random') {
				this.single 	= 0;
				this.min 		= 0;
				this.max 		= 0;
				this.seconds 	= 0;
				this.numberInp 	= false;
				this.countableInp 	= false;
				this.randomInp 	= true;
				this.countup 	= 0;
			}
			if (this.types == 'single') {
				this.numberInp 	= true;
				this.randomInp 	= false;
				this.countableInp 	= false;
				this.single 	= 0;
				this.min 		= 0;
				this.max 		= 0;
				this.seconds 	= 0;
				this.countup 	= 0;
			}
		},
		edit_current_type: function (event) {
			this.edit_types = event.target.value;
			jQuery('.edit_number').val(0);
			jQuery('.edit_min').val(0);
			jQuery('.edit_max').val(0);
			jQuery('.edit_countup').val(0);
			if (this.edit_types == 'random') {
				this.edit_numberInp 	= false;
				this.edit_randomInp 	= true;
				this.edit_countdown 	= false;
				this.editcountup 		= 0;
			}
			if (this.edit_types == 'edit_countup') {
				this.edit_numberInp 	= false;
				this.edit_randomInp 	= false;
				this.edit_countdown 	= true;
				this.editcountup 		= 1;
			}
			if (this.edit_types == 'edit_countdown') {
				this.edit_numberInp 	= false;
				this.edit_randomInp 	= false;
				this.edit_countdown 	= true;
				this.editcountup 		= 0;
			}
			if (this.edit_types == 'single') {
				this.edit_numberInp 	= true;
				this.edit_randomInp 	= false;
				this.edit_countdown 	= false;
				this.editcountup 		= 0;
			}
		},
		delete_entry: function (id) {
			let entry_id = id;
			if (!confirm("Are you sure?")) {
				return;
			} else {
				jQuery.ajax({
					type: "post",
					url: progress_entries.ajaxurl,
					data: {
						action: "delete_entry",
						entry_id: entry_id
					},
					success: function (response) {
						location.reload();
					}
				});
			}
		},
		typeselect: function (id) {
			let types = jQuery('.edittype'+id).val();
			if (types == 'random') {
				this.edit_randomInp = true;
				this.edit_countdown = false;
				this.edit_numberInp = false;
				this.editcountup 	= 0;
			}
			if (types == 'edit_countup') {
				this.edit_countdown = true;
				this.edit_randomInp = false;
				this.edit_numberInp = false;
				this.editcountup 	= 1;
			}
			if (types == 'edit_countdown') {
				this.edit_countdown = true;
				this.edit_randomInp = false;
				this.edit_numberInp = false;
				this.editcountup 	= 0;
			}
			if (types == 'single') {
				this.edit_randomInp = false;
				this.edit_countdown = false;
				this.edit_numberInp = true;
				this.editcountup 	= 0;
			}
		},
		update_entry: function (id) {
			jQuery('.editentrysave').prop('disabled', true);
			let parent = this;
			let entry_id = id;
			let entryName = jQuery('.edit_entryname'+entry_id).val();
			let textcolor = jQuery('.edit_textcolor'+entry_id).val();
			let numbercolor = jQuery('.edit_numbercolor' + entry_id).val();
			let borderswitch = '';
			if (jQuery('.bswitch' + entry_id).prop('checked') == true) {
				borderswitch = 'true';
			} else {
				borderswitch = 'false';
			}
			
			let bordercolor = jQuery('.edit_bordercolor'+entry_id).val();
			let fontsize = jQuery('.edit_fontsize'+entry_id).val();
			let edit_left = jQuery('.edit_left'+entry_id).val();
			let edit_number = jQuery('.edit_number'+entry_id).val();
			let edit_min = jQuery('.edit_min'+entry_id).val();
			let edit_max = jQuery('.edit_max'+entry_id).val();
			let seconds = jQuery('.edit_seconds'+entry_id).val();
			let edit_right = jQuery('.edit_right'+entry_id).val();
			let editcountup = this.editcountup;
			if (!edit_number) {
				edit_number = 0;
			}
			if (!edit_min) {
				edit_min = 0;
			}
			if (!edit_max) {
				edit_max = 0;
			}
			if (!seconds) {
				seconds = 0;
			}

			let data = {
				entry_id,
				entryName,
				textcolor,
				numbercolor,
				borderswitch,
				bordercolor,
				fontsize,
				edit_left,
				edit_number,
				edit_min,
				edit_max,
				seconds,
				editcountup,
				edit_right
			};
			
			jQuery.ajax({
				type: "post",
				url: progress_entries.ajaxurl,
				data: {
					action: "progress_update_entry",
					data:data
				},
				dataType: "json",
				success: function (response) {
					if (response.error) {
						jQuery('.editentrysave').removeAttr('disabled');
						parent.warning = "Trying without required values!";
					}
					if (response.success) {
						location.reload();
					}
				}
			});
		},
		addEntry: function () {
			let parent = this;
			this.isDisabled = true;
			let entryName = this.entryName;
			let leftSlot = this.leftslot;
			let number = this.single;
			let min = this.min;
			let max = this.max;
			let seconds = this.seconds;
			let rightSlot = this.rightslot;
			let countup = this.countup;

			let textcolor = this.textcolor;
			let numbercolor = this.numbercolor;
			let borderswitch = this.borderswitch;
			let bordercolor = this.bordercolor;
			let fontsize = this.fontsize;

			if (this.single > 0) {
				seconds = 0;
			}

			if (this.countableInp == true && (seconds == '' || seconds == '0')) {
				this.warning = "Trying without required values!";
				parent.isDisabled = false;
				return false;
			}

			let data = {entryName,leftSlot, number, min, max, rightSlot, countup, textcolor, numbercolor, bordercolor, fontsize,seconds,borderswitch};

			jQuery.ajax({
				type: "post",
				url: progress_entries.ajaxurl,
				data: {
					action: "progress_entries_save",
					data: data
				},
				dataType: "json",
				success: function (response) {
					if (response.success) {
						location.reload();
					}
					if (response.error) {
						parent.warning = "Trying without required values!";
						parent.isDisabled = false;
					}
				}
			});
		},
	},
	created: function () {
		// 
	}
});

jQuery(function ($) {
	$('#entries_table').dataTable();
	$('.edit-entry').each(function () {
		$(document).on('click','.edit-entry', function () {
			$('.edit_popup').hide();
			$(this).parent().children('.edit_popup').show();
		});
	});
	$(document).on('click','.closeedit', function () {
		$(this).parent().parent().hide();
	});

	$('.borderswitch').each(function () {
		$(document).on("change",'.borderswitch', function () {
			if ($(this).prop('checked') == true) {
				$(this).parent().next().children('label[for="bordercolor"]').show();
			} else {
				$(this).removeAttr('checked')
				$(this).parent().next().children('label[for="bordercolor"]').hide();
			}
		});
	});
});
