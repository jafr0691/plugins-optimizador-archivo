jQuery(document).ready(function($){

  var MediaLibraryReduccionFilter = wp.media.view.AttachmentFilters.extend({
    tagName:   'button',
    id: 'media-attachment-reduccion-filter',
    className:'button media-button spinner is-active',
    events: {
        click: 'click'
    },
    click: function() {
      $.ajax({
        url: sqlinforeduc.reducajax,
        type: "post",
        data: {action:'sqlinforeduc',acti:'reducir'},
        beforeSend: function() {
            $('#media-attachment-reduccion-filter').css('margin-right','0.4em');
            document.getElementById('media-attachment-reduccion-filter').innerHTML =  'Comprimiendo Archivos';
            $('.spinner').attr('class', 'spinner is-active');
        },
        success: function(dato) {
            var dd = JSON.parse(dato);
            console.log(dd);
            $('#media-attachment-reduccion-filter').css('margin-right','0.4em');
            document.getElementById('media-attachment-reduccion-filter').innerHTML =  dd.peso;
            $('.spinner').attr('class', 'spinner');

            if(dd.rrr!=0){
                console.log(dd.rrr);
                setTimeout(()=>{$('#media-attachment-reduccion-filter').click();}, 5000);
            }else{
                document.getElementById('media-attachment-reduccion-filter').innerHTML =  dd.peso;
                $('#media-attachment-reduccion-filter').off();

            }
        },
      error: function(){
        $('#media-attachment-reduccion-filter').attr('class','button media-button');
        $('#media-attachment-reduccion-filter').css('margin-right','0.4em');
        $('.spinner').attr('class', 'spinner');
        $('#media-attachment-reduccion-filter').html('Seguir Reduciendo peso');
      }
      });
    },
    createFilters: function() {
      var filters = {};
      filters.all = {
        text:  '',
        priority: 10
      };
      this.filters = filters;
    }
  });

  var AttachmentsBrowser = wp.media.view.AttachmentsBrowser;
  wp.media.view.AttachmentsBrowser = wp.media.view.AttachmentsBrowser.extend({
    createToolbar() {
      AttachmentsBrowser.prototype.createToolbar.call( this );
      this.toolbar.set( 'MediaLibraryReduccionFilter', new MediaLibraryReduccionFilter({
            controller: this.controller,
            model:      this.collection.props,
        priority: -75
      }).render() );
    }
  });


  $.ajax({
      url: sqlinforeduc.reducajax,
      type: 'post',
      data: {action:'sqlinforeduc',acti:'info'},
      beforeSend: function() {
        $(".spinner").addClass("is-active");
      },
      success: function(dato) {
          var d = JSON.parse(dato);
          console.log(d);
        if(document.getElementById('reduclist')){
            if(d.indiceb<=0){
                $("#reduclist").replaceWith('<button class="button media-button" id="media-attachment-reduccion-filter" style="margin-right: 0.4em;">Optimizado '+d.peso+'</button>');
                $('#media-attachment-reduccion-filter').off();
            }else{
                $("#reduclist").replaceWith('<button class="button media-button" id="media-attachment-reduccion-filter" style="margin-right: 0.4em;">Optimizar '+d.indiceb+' Archivos'+'</button>');
            }
            
            if(d.actibtn){
                $('#media-attachment-reduccion-filter').on('click', function(event){
                    event.preventDefault();
                    var pluginsUrl = location.origin+location.pathname;
                  $.ajax({
                    url: sqlinforeduc.reducajax,
                    type: "post",
                    data: {action:'sqlinforeduc',acti:'reducir'},
                    beforeSend: function() {
                      $('#media-attachment-reduccion-filter').html('<img src="'+pluginsUrl+'/../../wp-content/plugins/reducir-peso-img/img/carga.gif" style="margin: 0;vertical-align:middle;" width="40" height="20">');
                    },
                    success: function(dato) {
                        var d = JSON.parse(dato);
                        $('#media-attachment-reduccion-filter').css('margin-right','0.4em');
                        document.getElementById('media-attachment-reduccion-filter').innerHTML =  d.peso;
                        $('.spinner').attr('class', 'spinner');
            
                        if(d.rrr!=0){
                            setTimeout(()=>{$('#media-attachment-reduccion-filter').click();}, 5000);
                        }else{
                            document.getElementById('media-attachment-reduccion-filter').innerHTML =  d.peso;
                            $('#media-attachment-reduccion-filter').off();
            
                        }
                    },
                      error: function(){
                        $('#media-attachment-reduccion-filter').attr('class','button media-button');
                        $('#media-attachment-reduccion-filter').css('margin-right','0.4em');
                        $('.spinner').attr('class', 'spinner');
                        $('#media-attachment-reduccion-filter').html('Seguir Reduciendo peso');
                      }
                  });
                });
            }
        }else{
            $('#media-attachment-reduccion-filter').attr('class','button media-button');
            $('#media-attachment-reduccion-filter').css('margin-right','0.4em');
            if(d.indiceb<=0){
                $('#media-attachment-reduccion-filter').off();
                if(d.peso==="0.00 B"){
                    $('#media-attachment-reduccion-filter').html('Vacio');
                }else{
                    $('#media-attachment-reduccion-filter').html('Optimizado '+d.peso);
                }
            }else{
                $('#media-attachment-reduccion-filter').html('Optimizar '+d.indiceb+' Archivos');
            }
            
        }
      },
      error: function(){
        $('#media-attachment-reduccion-filter').attr('class','button media-button');
        $('#media-attachment-reduccion-filter').css('margin-right','0.4em');
        $('#media-attachment-reduccion-filter').html('ERROR');
        $('#media-attachment-reduccion-filter').off();
      }
  });
  

});