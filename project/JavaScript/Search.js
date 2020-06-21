$("[name='SearchSubmit']").click(function () {
    let TitleSearchContent = document.getElementsByName("Title")[0];
    let DescriptionSearchContent = document.getElementsByName("Description")[0];
    let searchChoice = $('input[name="SearchMethod"]:checked').val();

    if(searchChoice === 'SearchByTitle'){
        $("#realQuery").val(TitleSearchContent.value);
    }else if(searchChoice === 'SearchByDescription'){
        $("#realQuery").val(DescriptionSearchContent.value);
    }else{
        TitleSearchContent.setCustomValidity("No ideas? Maybe Browser page is a better choice");
    }
    $("#SearchForm").submit();
});
