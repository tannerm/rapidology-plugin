(function($){
	$(document).ready(function() {
		var $locked_containers = [];
		$( '.flm_custom_html_form input[type="radio"], .flm_custom_html_form input[type="checkbox"]' ).uniform();


		var flmCountDown = function(clock) {
			var SELF = this;

			SELF.getTimeRemaining = function(endtime, offset){
				var t = endtime - Date.parse(new Date()) + offset;
				var seconds = Math.floor( (t/1000) % 60 );
				var minutes = Math.floor( (t/1000/60) % 60 );
				var hours = Math.floor( (t/(1000*60*60)) % 24 );
				var days = Math.floor( t/(1000*60*60*24) );
				return {
					'total': t,
					'days': days,
					'hours': hours,
					'minutes': minutes,
					'seconds': seconds
				};
			};

			SELF.initializeClock = function(clock){

				if ( null == clock ) {
					return;
				}

				var daysSpan = clock.querySelector('.days');
				var hoursSpan = clock.querySelector('.hours');
				var minutesSpan = clock.querySelector('.minutes');
				var secondsSpan = clock.querySelector('.seconds');

				var endtime = parseInt( clock.getAttribute('data-duration') + '000' );
				var offset  = parseInt( clock.getAttribute('data-offset') + '000' );

				function updateClock(){
					var t = SELF.getTimeRemaining(endtime, offset);

					daysSpan.innerHTML = t.days;
					hoursSpan.innerHTML = ('0' + t.hours).slice(-2);
					minutesSpan.innerHTML = ('0' + t.minutes).slice(-2);
					secondsSpan.innerHTML = ('0' + t.seconds).slice(-2);

					if(t.total<=0){
						clearInterval(timeinterval);
					}
				}

				updateClock();
				var timeinterval = setInterval(updateClock,1000);
			};

			SELF.initializeClock(clock);
		};

		$('.flm-countdown').each(function(){
			new flmCountDown(this);
		});

		var $body = $('body');
		$body.on( 'click', 'span.flm_close_button', function(){
			var container = $( this ).parent().parent();

			container.addClass( 'flm_exit_animation' );

			setTimeout( function() {
				container.remove();
			}, 400 );

			$( 'body' ).removeClass( 'flm_popup_active' );

			return false;
		});

		function update_stats_table( $type, $this_button ) {
			var $optin_id = $this_button.data( 'optin_id' ),
				$page_id = $this_button.data( 'page_id' ),
				$list_id = $this_button.data( 'list_id' );

			var $stats_data = JSON.stringify({
				'type': $type,
				'optin_id': $optin_id,
				'page_id': $page_id,
				'list_id': $list_id
			});
			$.ajax({
				type: 'POST',
				url: flmSettings.ajaxurl,
				data: {
					action : 'flm_handle_stats_adding',
					stats_data_array : $stats_data,
					update_stats_nonce : flmSettings.stats_nonce
				}
			});
		}

		function setCookieExpire( days ) {
			var ms = days*24*60*60*1000;

			var date = new Date();
			date.setTime( date.getTime() + ms );

			return "; expires=" + date.toUTCString();
		}

		function checkCookieValue( cookieName, value ) {
			return parseCookies()[cookieName] == value;
		}

		function parseCookies() {
			var cookies = document.cookie.split( '; ' );

			var ret = {};
			for ( var i = cookies.length - 1; i >= 0; i-- ) {
			  var el = cookies[i].split( '=' );
			  ret[el[0]] = el[1];
			}
			return ret;
		}

		function set_cookie( $expire, $cookie_content ) {
			$cookie_content = '' == $cookie_content ? 'etFLMCookie=true' : $cookie_content;
			cookieExpire = setCookieExpire( $expire );
			document.cookie = $cookie_content + cookieExpire + "; path=/";
		}

		function get_url_parameter( param_name ) {
			var page_url = window.location.search.substring(1);
			var url_variables = page_url.split('&');
			for ( var i = 0; i < url_variables.length; i++ ) {
					var curr_param_name = url_variables[i].split( '=' );
				if ( curr_param_name[0] == param_name ) {
					return curr_param_name[1];
				}
			}
		}

		//separate function for the setTimeout to make it work properly within the loop.
		function make_popup_visible( $popup, $delay, $cookie_exp, $cookie_content ){
			if ( ! $popup.hasClass( 'flm_visible' ) ) {
				setTimeout( function() {
					$popup.addClass( 'flm_visible flm_animated' );
					$stats_data_container = 0 != $popup.find( '.flm_custom_html_form' ).length ? $popup.find( '.flm_custom_html_form' ) : $popup.find( '.flm_submit_subscription' );
					update_stats_table( 'imp', $stats_data_container );

					if ( '' != $cookie_exp ) {
						set_cookie( $cookie_exp, $cookie_content );
					}

					if ( $( '.flm_resize' ).length ) {
						$( '.flm_resize.flm_visible' ).each( function() {
							define_popup_position( $( this ), true, 0 );
						});
					}

					display_image( $popup );

				}, $delay );
			}
		}

		function display_image( $popup ) {
			setTimeout( function() {
				$popup.find( '.flm_image' ).addClass( 'flm_visible_image' );
			}, 500 );
		}

		function auto_popup( $current_popup_auto, $delay ) {
			var page_id = $current_popup_auto.find( '.flm_submit_subscription' ).data( 'page_id' ),
				optin_id = $current_popup_auto.find( '.flm_submit_subscription' ).data( 'optin_id' ),
				list_id = $current_popup_auto.find( '.flm_submit_subscription' ).data( 'list_id' );

			if ( ! $current_popup_auto.hasClass( 'flm_animated' ) ) {
				var $cookies_expire_auto = $current_popup_auto.data( 'cookie_duration' ) ? $current_popup_auto.data( 'cookie_duration' ) : false,
					$already_subscribed = checkCookieValue( 'flm_subscribed_to_' + optin_id + list_id, 'true' );

				if ( ( ( false !== $cookies_expire_auto && ! checkCookieValue( 'etFLMCookie_' + optin_id, 'true' ) ) || false == $cookies_expire_auto ) && ! $already_subscribed ) {
					if ( false !== $cookies_expire_auto ) {
						make_popup_visible ( $current_popup_auto, $delay, $cookies_expire_auto, 'etFLMCookie_' + optin_id + '=true' );
					} else {
						make_popup_visible ( $current_popup_auto, $delay, '', '' );
					}
				}
			}
		}


        $('.flm_click_trigger_element').on('click', function(e){
            var optin_id = $(this).data('optin_id')
            $( '.flm_click_trigger:not(.flm_visible)' ).each( function() {
                var $this_el = $( this );
                current_optin_id = $(this).find( '.flm_submit_subscription' ).data( 'optin_id' );
                e.preventDefault();//prevent links from disrupting popup
                if(current_optin_id == optin_id){
                    make_popup_visible ( $this_el, 0, '', '' );
                }
            });

        });

        function exit_trigger($current_popup_exit){

            var page_id = $current_popup_exit.find( '.flm_submit_subscription' ).data( 'page_id' ),
                optin_id = $current_popup_exit.find( '.flm_submit_subscription' ).data( 'optin_id' ),
                list_id = $current_popup_exit.find( '.flm_submit_subscription' ).data( 'list_id' );

            if ( ! $current_popup_exit.hasClass( 'flm_animated' ) ) {
                var $cookies_expire_auto = $current_popup_exit.data( 'cookie_duration' ) ? $current_popup_exit.data( 'cookie_duration' ) : false,
                    $already_subscribed = checkCookieValue( 'flm_subscribed_to_' + optin_id + list_id, 'true' );

                $( document ).mouseleave(function() {
                    if (( ( false !== $cookies_expire_auto && !checkCookieValue('etFLMCookie_' + optin_id, 'true') ) || false == $cookies_expire_auto ) && !$already_subscribed) {
                        if (false !== $cookies_expire_auto) {

                            return make_popup_visible($current_popup_exit, 0, $cookies_expire_auto, 'etFLMCookie_' + optin_id + '=true');

                        } else {

                            return make_popup_visible($current_popup_exit, 0, '', '');
                        }
                    }
                });
            }
        }


		function scroll_trigger( current_popup_bottom, is_bottom_trigger ) {
			var triggered = 0,
				page_id = current_popup_bottom.find( '.flm_submit_subscription' ).data( 'page_id' ),
				optin_id = current_popup_bottom.find( '.flm_submit_subscription' ).data( 'optin_id' );
				list_id = current_popup_bottom.find( '.flm_submit_subscription' ).data( 'list_id' );

			if ( ! current_popup_bottom.hasClass( 'flm_animated' ) ) {
				var	cookies_expire_bottom = current_popup_bottom.data( 'cookie_duration' ) ? current_popup_bottom.data( 'cookie_duration' ) : false,
					$already_subscribed = checkCookieValue( 'flm_subscribed_to_' + optin_id + list_id, 'true' );

				var scroll_trigger = undefined;
				if ( true == is_bottom_trigger ) {
					var $flmBottomTrigger = $('.flm_bottom_trigger');
                    scroll_trigger = $flmBottomTrigger.length ? $flmBottomTrigger.offset().top : $( document ).height() - 500;
				} else {
					var scroll_pos = current_popup_bottom.data( 'scroll_pos' ) > 100 ? 100 : current_popup_bottom.data( 'scroll_pos' );
					scroll_trigger = 100 == scroll_pos ? $( document ).height() - 50 : $( document ).height() * scroll_pos / 100;
				}
				//check document height vs window height( if its the same or less assume mobile and show slidein after 5 seconds)
				if ($(document).height() <= $(window).height()){
					setTimeout(
						function(){
							make_popup_visible ( current_popup_bottom, 0, '', '' );
						}, 5000
					);
				}
				$( window ).scroll( function(){
					if ( ( ( false !== cookies_expire_bottom && ! checkCookieValue( 'etFLMCookie_' + optin_id, 'true' ) ) || false == cookies_expire_bottom ) && ! $already_subscribed ) {
						if( $( window ).scrollTop() + $( window ).height() > scroll_trigger ) {
							if ( 0 == triggered ) {
								if ( false !== cookies_expire_bottom ) {
									make_popup_visible ( current_popup_bottom, 0, cookies_expire_bottom, 'etFLMCookie_' + optin_id + '=true' );
								} else {
									make_popup_visible ( current_popup_bottom, 0, '', '' );
								}

								triggered++;
							}
						}
					}
				});
			}
		}

		 if( $( '.flm_auto_popup' ).length ) {
			$( '.flm_auto_popup:not(.flm_visible)' ).each( function() {
				var this_el = $( this ),
					delay = '' !== this_el.data( 'delay' ) ? this_el.data( 'delay' ) * 1000 : 0;
				auto_popup( this_el, delay );
			});
		 }

		if( $( '.flm_trigger_bottom' ).length ) {

			$( '.flm_trigger_bottom:not(.flm_visible)' ).each( function(){
				scroll_trigger( $( this ), true );
			});

		}

        if( $( '.flm_before_exit' ).length ) {

            $( '.flm_before_exit:not(.flm_visible)' ).each( function(){
                exit_trigger( $( this ), false );
            });

        }

		if( $( '.flm_scroll' ).length ) {

			$( '.flm_scroll:not(.flm_visible)' ).each( function(){
				scroll_trigger( $( this ), false );
			});
		}

		if( $( '.flm_trigger_idle' ).length ) {
			$( '.flm_trigger_idle:not(.flm_visible)' ).each( function() {
				var this_el = $( this ),
					page_id = this_el.find( '.flm_submit_subscription' ).data( 'page_id' ),
					optin_id = this_el.find( '.flm_submit_subscription' ).data( 'optin_id' ),
					list_id = this_el.find( '.flm_submit_subscription' ).data( 'list_id' );

				if ( ! this_el.hasClass( 'flm_animated' ) ) {
					var $cookies_expire_idle = this_el.data( 'cookie_duration' ) ? this_el.data( 'cookie_duration' ) : false,
						$already_subscribed = checkCookieValue( 'flm_subscribed_to_' + optin_id + list_id, 'true' );
					var $idle_timeout = '' !== this_el.data( 'idle_timeout' ) ? this_el.data( 'idle_timeout' ) * 1000 : 30000,
						$delay = 0;

					if ( ( ( false !== $cookies_expire_idle && ! checkCookieValue( 'etFLMCookie_' + optin_id, 'true' ) ) || false == $cookies_expire_idle ) && ! $already_subscribed ) {
						$( document ).idleTimer( $idle_timeout );

						$( document ).on( 'idle.idleTimer', function() {
							if ( false !== $cookies_expire_idle ) {
								make_popup_visible ( this_el, $delay, $cookies_expire_idle, 'etFLMCookie_' + optin_id + '=true' );
							} else {
								make_popup_visible ( this_el, $delay, '', '' );
							}
						});
					}
				}
			});
		}

		if ( 'true' == get_url_parameter( 'flm_popup' ) ) {
			$( '.flm_after_comment' ).each( function() {
				auto_popup( $( this ), 0 );
			});
		}

		if ( $( '.flm_after_order' ).length ) {
			$( '.flm_after_purchase' ).each( function() {
				auto_popup( $( this ), 0 );
			});
		}

		var $flmLockedContainer = $('.flm_locked_container');
        if( $flmLockedContainer.length ) {
			var $i = 0;

			$flmLockedContainer.each( function() {
				var $this_el = $( this ),
					content = $this_el.find( '.flm_locked_content' ),
					form = $this_el.find( '.flm_locked_form' ),
					page_id = $this_el.data( 'page_id' ),
					optin_id = $this_el.data( 'optin_id' );

				$this_el.data( 'container_id', $i );
				$locked_containers.push( content );

				if ( checkCookieValue( 'flm_unlocked' + optin_id + page_id, 'true' ) ) {
					content.css( {'display' : 'block'} );
					form.remove();
				} else {
					content.remove();
					update_stats_table( 'imp', $this_el );
				}

				$i++;
			});
		}

		$body.on( 'click', '.flm_locked_container .flm_submit_subscription', function(){
			var current_container = $( this ).closest( '.flm_locked_container' ),
				container_id = current_container.data( 'container_id' ),
				page_id = current_container.data( 'page_id' ),
				optin_id = current_container.data( 'optin_id' );

			perform_subscription( $( this ), current_container, container_id, page_id, optin_id );

			return false;
		});

		// unlock content immediately if custom HTML form is used.
		$body.on( 'click', '.flm_locked_container .flm_custom_html_form input[type="submit"], .flm_locked_container .flm_custom_html_form button[type="submit"]', function() {
			var current_container = $( this ).closest( '.flm_locked_container' ),
				container_id = current_container.data( 'container_id' ),
				page_id = current_container.data( 'page_id' ),
				optin_id = current_container.data( 'optin_id' );

			unlock_content( current_container, container_id, page_id, optin_id );
		} );

		function unlock_content( current_container, container_id, locked_page_id, locked_optin_id ) {
			set_cookie( 365, 'flm_unlocked' + locked_optin_id + locked_page_id + '=true' );
			current_container.find( '.flm_locked_form' ).replaceWith( $locked_containers[container_id] );
			current_container.find( '.flm_locked_content' ).css( { 'display' : 'block' } );
		}

		// Move inline forms into appropriate sections in Divi theme
		var $flmBelowPost = $('.flm_below_post');
        if( $flmBelowPost.length ) {
			if ( $body.hasClass( 'rad_pb_pagebuilder_layout' ) ) {
				var bottom_inline = $flmBelowPost,
					divi_container = '<div class="rad_pb_row"><div class="rad_pb_column ra_pb_column_4_4"></div></div>';

				if ( bottom_inline.length ) {
					$( '.rad_pb_section' ).not( '.rad_pb_fullwidth_section' ).last().append( divi_container ).find( '.rad_pb_row' ).last().find( '.rad_pb_column' ).append( bottom_inline );
				}
			}
		}

		function define_popup_position( $this_popup, $just_loaded, $message_space ) {
			var this_popup = $this_popup.find( '.flm_form_container' ),
				popup_max_height = this_popup.hasClass( 'flm_popup_container' ) ? $( window ).height() - 40 : $( window ).height() - 20,
				real_popup_height = 0,
				flyin_percentage = this_popup.parent().hasClass( 'flm_flyin' ) ? 0.03 : 0.05,
				percentage = this_popup.hasClass( 'flm_with_border' ) ? flyin_percentage + 0.03 : flyin_percentage,
				breakout_offset = this_popup.hasClass( 'breakout_edge' ) ? 0.95 : 1,
				dashed_offset = this_popup.hasClass( 'flm_border_dashed' ) ? 4 : 0,
				form_height = this_popup.find( 'form' ).innerHeight() + $message_space,
				form_add = true == $just_loaded ? 5 : 0;

			var header_height = undefined;
			if ( this_popup.find( '.flm_form_header' ).hasClass('split' ) ) {
				var image_height = this_popup.find( '.flm_form_header img' ).innerHeight(),
					text_height = this_popup.find( '.flm_form_header .flm_form_text' ).innerHeight();
				header_height = image_height < text_height ? text_height + 30 : image_height + 30;
			} else {
				header_height = this_popup.find( '.flm_form_header img' ).innerHeight() + this_popup.find( '.flm_form_header .flm_form_text' ).innerHeight() + 30;
			}

			this_popup.css( { 'max-height' : popup_max_height } );

			if ( this_popup.hasClass( 'flm_popup_container' ) && ! this_popup.parent().hasClass( 'flm_inline_form' ) ) {
				var top_position = $( window ).height() / 2 - this_popup.innerHeight() / 2;
				this_popup.css( { 'top' : top_position + 'px' } );
			}

			this_popup.find( '.flm_form_container_wrapper' ).css( { 'max-height' : popup_max_height - 20 } );


			var $body2 = $('body');
            if ( ( 768 > $body2.outerWidth() + 15 ) || this_popup.hasClass( 'flm_form_bottom' ) ) {
				if ( this_popup.hasClass( 'flm_form_right' ) || this_popup.hasClass( 'flm_form_left' ) ) {
					this_popup.find( '.flm_form_header' ).css( { 'height' : 'auto' } );
				}

				real_popup_height = this_popup.find( '.flm_form_header' ).innerHeight() + this_popup.find( '.flm_form_content' ).innerHeight() + 30 + form_add;

				if ( this_popup.hasClass( 'flm_form_right' ) || this_popup.hasClass( 'flm_form_left' ) ) {
					this_popup.find( '.flm_form_container_wrapper' ).css( { 'height' : real_popup_height - 30 + dashed_offset } );
				}
			} else {
				if ( header_height < form_height ) {
					real_popup_height = this_popup.find( 'form' ).innerHeight() + 30 + $message_space;
				} else {
					real_popup_height = header_height + 30;
				}

				if ( this_popup.hasClass( 'flm_form_right' ) || this_popup.hasClass( 'flm_form_left' ) ) {
					this_popup.find( '.flm_form_header' ).css( { 'height' : real_popup_height * breakout_offset - dashed_offset } );
					this_popup.find( '.flm_form_content' ).css( { 'min-height' : real_popup_height - dashed_offset } );
					this_popup.find( '.flm_form_container_wrapper' ).css( { 'height' : real_popup_height } );
				}
			}

			if ( real_popup_height > popup_max_height ) {
				this_popup.find( '.flm_form_container_wrapper' ).addClass( 'flm_vertical_scroll' );
			} else {
				this_popup.find( '.flm_form_container_wrapper' ).removeClass( 'flm_vertical_scroll' );
			}

			if ( $this_popup.hasClass( 'flm_popup' ) ) {
				$body2.addClass( 'flm_popup_active' );
			}
		}

		$body.on( 'click', '.flm_submit_subscription', function() {
			perform_subscription( $( this ), '', '', '', '' );
			return false;
		});

		function perform_subscription( this_button, current_container, container_id, locked_page_id, locked_optin_id ) {
			var this_form = this_button.parent(),
				list_id = this_button.data( 'list_id' ),
				account_name = this_button.data( 'account' ),
				service = this_button.data( 'service' ),
				redirect_behavior = this_button.data( 'redirect_behavior'),
				name = this_form.find( '.flm_subscribe_name input' ).val(),
				last_name = undefined != this_form.find( '.flm_subscribe_last input' ).val() ? this_form.find( '.flm_subscribe_last input' ).val() : '',
				email = this_form.find( '.flm_subscribe_email input' ).val(),
				page_id = this_button.data( 'page_id' ),
                disable_dbl_optin = this_button.data( 'disable_dbl_optin'),
                post_name = this_button.data('post_name'),
                cookie = this_button.data('cookie');
                optin_id = this_button.data( 'optin_id' );

			this_form.find( '.flm_subscribe_email input' ).removeClass( 'flm_warn_field' );

			if ( '' == email ) {
				this_form.find( '.flm_subscribe_email input' ).addClass( 'flm_warn_field' );
			} else {
				$subscribe_data = JSON.stringify({ 'list_id' : list_id, 'account_name' : account_name, 'service' : service, 'name' : name, 'email' : email, 'page_id' : page_id, 'optin_id' : optin_id, 'last_name' : last_name, 'dbl_optin' : disable_dbl_optin, 'post_name' : post_name, 'cookie' : cookie });
				$.ajax({
					type: 'POST',
					dataType: 'json',
					url: flmSettings.ajaxurl,
					data: {
						action : 'flm_subscribe',
						subscribe_data_array : $subscribe_data,
						subscribe_nonce : flmSettings.subscribe_nonce
					},
					beforeSend: function( data ) {
						this_button.addClass( 'flm_button_text_loading' );
						this_button.find( '.flm_subscribe_loader' ).css( { 'display' : 'block' } );
					},
					success: function( data ) {
						this_button.removeClass( 'flm_button_text_loading' );
						this_button.find( '.flm_subscribe_loader' ).css( { 'display' : 'none' } );
						if ( data ) {
							if ( '' != current_container && ( data.success || 'Invalid email' != data.error ) ) {
								unlock_content( current_container, container_id, locked_page_id, locked_optin_id );
							} else {
								if ( data.error ) {
									this_form.find( '.flm_error_message' ).remove();
									this_form.prepend( '<h2 class="flm_error_message">' + data.error + '</h2>' );
									this_form.parent().parent().find( '.flm_form_header' ).addClass( 'flm_with_error' );
								}
								if ( data.success && '' == current_container ) {
									this_form.parent().find( '.flm_success_message' ).addClass( 'flm_animate_message' );
									this_form.parent().find( '.flm_success_container' ).addClass( 'flm_animate_success' );
									this_form.remove();
									set_cookie( 365, 'flm_subscribed_to_' + optin_id + list_id + '=true' );
								}
							}

							if ( undefined != data.redirect ) {
								redirect_behavior = ( '_self' == redirect_behavior ) ? '_self' : '_blank';
								window.open(data.redirect, redirect_behavior);
							}

							define_popup_position( this_form.parent().parent().parent().parent(), false, 50 );
						}
					}
				});
			}
		}

		$body.on( 'click', '.flm_custom_html_form input[type="submit"], .flm_custom_html_form button[type="submit"]', function() {
			var this_button = $( this ),
				form_container = this_button.closest( '.flm_custom_html_form' );

			update_stats_table( 'con', form_container );
		} );

		$( window ).resize( function(){
			var $flmResize = $('.flm_resize');
            if ( $flmResize.length ) {
				$flmResize.each( function() {
					define_popup_position( $( this ), false, 0 );
				});
			}
		});
	});
})(jQuery);
//once the window is loaded make sure that the body tag has the required class for flm to work
(function($){
    $(window).load(function(){
       if(!$('body').hasClass('flm')){
           $('body').addClass('flm');
       }
    });
}(jQuery));