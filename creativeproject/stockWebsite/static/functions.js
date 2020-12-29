$( ".bm" ).click(function(){
    const link = $( this ).siblings(" .bookmarks ").find(":selected").val()
    window.open(
        link, '_blank'
    )
})