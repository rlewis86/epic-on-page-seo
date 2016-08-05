jQuery(document)
    .ready(function($) {

            // Declare Global Variables
            var siteUrls = new Array();
            
            $('#lblError').text('');
            
            // Disable Submit button if not api key or search engine id
            //debugger;
            
            var apiKeyLabel = $('#lblApiKey');
            var apiKey = apiKeyLabel.attr('value');
            var searchEngineIdLabel = $('#lblSearchEngineKey');
            var searchEngineId = searchEngineIdLabel.attr('value');
            
            if(!apiKey || !searchEngineId){
            	$('#lblError').text('You must enter an api key and search engine id in the settings section!');
            	$('#btnSubmitKeyword').attr("disabled","disabled");
            } else {
            	$('#btnSubmitKeyword').removeAttr("disabled");
            }
            

            // Get Form Submit
          //  $('#frmKeyword').submit(
                 //   function(e) {
                     $('body').on('submit','#frmKeyword', function(e){    
                    	$('#lblError').text('')
                        var apiKeyLabel = $('#lblApiKey');
                        var apiKey = apiKeyLabel.attr('value');
                        var searchEngineIdLabel = $('#lblSearchEngineKey');
                        var searchEngineId = searchEngineIdLabel.attr('value');
                        var keyword = $('#txtkeyword').val();
                        var encodedKeyword = encodeURIComponent(keyword);
                       // debugger;
                        
                        var urlString = 'https://www.googleapis.com/customsearch/v1?key=' + apiKey+ '&cx=' + searchEngineId + '&q=' + encodedKeyword

                        $('#tables-group').fadeOut("slow");
                        $.blockUI();
                        e.preventDefault();
                        $.ajax({
                                type: 'GET',
                                timeout: 60000,
                                url: urlString
                            })
                            .done(function(data) {
                                    // If success
                                    if(data === ""){
                                        $('#lblError').text('An Error Has Occured. Please Try Again Later or Contact Support');
                                        $.unblockUI();
                                        return;
                                    }
                                    
                                    if (data === 'An Error Has Occured. Please Try Again Later or Contact Support'){
                                            $('#lblError').text('An Error Has Occured. Please Try Again Later or Contact Support');
                                            $.unblockUI();
                                            return;
                                    }
                                    
                                    siteUrls = [];

                                	//store site URLs in global variable
                                    $.each(data.items, function(i, item) {
                                        siteUrls.push(item.link);
                                    });
                                  //  debugger;
                                    // Get Page Attributes
                                    getPageData(siteUrls)
                                    
                                })
                            .fail(function(data) {
                                    // If failed
                                    $('#lblError').text('An error has occured.  Please try again.')
                                    $.unblockUI();
                                });

                    });

            function getPageData(urls) {
                var keyword = $('#txtkeyword').val();
                $.ajax({
                        type: 'post',
                        url: $('#frmKeyword').attr('action'),
                        timeout: 60000,
                        data: {
                            urls: urls,
                            keyword: keyword
                        }
                    })
                    .done(function(data) {
                        if(data === ""){
                            $('#lblError').text('An Error Has Occured. Please Try Again Later or Contact Support');
                            $.unblockUI();
                            return;
                        }
                        
                        if (data === 'An Error Has Occured. Please Try Again Later or Contact Support')
                        {
                                $('#lblError').text('An Error Has Occured. Please Try Again Later or Contact Support');
                                $.unblockUI();
                        }

                        try{
                        buildTables(data);
                        
                        } catch(err){
                        	$('#lblError').text('An Error Has Occured. Please Try Again Later or Contact Support');
                            $.unblockUI();
                        }
                    })
                    .fail(function(data) {
                            // If failed
                            $('#lblError').text('An Error Has Occured. Please Try Again Later or Contact Support')
                            $.unblockUI();
                    });

            }

            $('#btnExcelExport').click(function(){
            	exportToCSV('tblSites');
            });
              
            // build out html tables
            function buildTables(data) {
                var htmlSites = '';
                var htmlMetaKeyword = '';
                var htmlMetaDescription = '';
                var htmlTitle = '';
                var htmlH1 = '';
                var htmlH2 = '';
                var htmlH3 = '';
                var htmlWordCount = '';
                var htmlKeywordDensity = '';
                var htmlImgAlts = '';
//                var htmlPageSpeed;
                var totalWordCount = 0;
                var i = 0;
                var keyword = $('#txtkeyword').val();
                var keywordArray = keyword.split(' ');
                 
                //debugger;
                if (data.substring(0,11) === '<pre></pre>'){
                	data = data.replace('<pre></pre>', '');
                }

                data = $.parseJSON(data);
                // Remove everything in table nut 1st row
                $('#tblSites tr').not(':first').remove();
                $('#tblMetaKeywords tr').not(':first').remove();
                $('#tblMetaDescription tr').not(':first').remove();
                $('#tblPageTitle tr').not(':first').remove();
                $('#tblPageH1 tr').not(':first').remove();
                $('#tblPageH2 tr').not(':first').remove();
                $('#tblPageH3 tr').not(':first').remove();
                $('#tblPageWords tr').not(':first').remove();
                $('#tblPageKWDensity tr').not(':first').remove();
                $('#tblImgAlts tr').not(':first').remove();
                $('#tblPageSpeed tr').not(':first').remove();

                // create html for tables
                $.each(
                    data,
                    function(index, value) {
                        
                        htmlSites += '<tr style="outline: thin solid"><td><a target="_blank" href="' + siteUrls[index] + '">' + siteUrls[index] + '</a></td><td style="text-align:center">' + data[index].internalLinks.toString() + '</td><td style="text-align:center">' + data[index].externalLinks.toString() + '</td></tr>';

                        // build meta keyword table and set background color
                        if (data[index].metaKeywords.toString() === 'Meta Keywords Not Used') {
                            htmlMetaKeyword += '<tr style="background:#FFA07A; outline: thin solid"><td>' + siteUrls[index] + '</td><td>' + data[index].metaKeywords.toString() + '</td></tr>';
                        } else {
                        	//if keyword appears, enlarge and turn red
                        	var metaKeywordHTML = '';
                        	metaKeywordHTML = wrapInTags({
                        		text: data[index].metaKeywords,
                        		tag: 'font size="3" color="red"',
                        		words: keywordArray
                        	});
                        		
                            htmlMetaKeyword += '<tr style="background:#98FB98; outline: thin solid"><td>' + siteUrls[index] + '</td><td>' + metaKeywordHTML + '</td></tr>';
                        }

                        // build meta keyword table and set background color
                        if (data[index].metaDescription
                            .toString() === 'Meta Description Not Used') {
                            htmlMetaDescription += '<tr style="background:#FFA07A; outline: thin solid"><td>' + siteUrls[index] + '</td><td>' + data[index].metaDescription.toString() + '</td></tr>';
                        } else {
                        	//if keyword appears, enlarge and turn red
                        	var metaDescriptionHTML = '';
                        	metaDescriptionHTML = wrapInTags({
                        		text: data[index].metaDescription,
                        		tag: 'font size="3" color="red"',
                        		words: keywordArray
                        	});
                            htmlMetaDescription += '<tr style="background:#98FB98; outline: thin solid"><td>' + siteUrls[index] + '</td><td>' + metaDescriptionHTML + '</td></tr>';
                        }

                      //if keyword appears in title, enlarge and turn red
                        var titleHTML = '';
                        titleHTML = wrapInTags({
                    		text: data[index].title,
                    		tag: 'font size="3" color="red"',
                    		words: keywordArray
                    	});
                        
                    	// build title table
                        htmlTitle += '<tr style="outline: thin solid"><td>' + siteUrls[index] + '</td><td>' + titleHTML + '</td></tr>';
                        
                      //if keyword appears in h1, enlarge and turn red
                        var h1HTML = '';
                        h1HTML = wrapInTags({
                    		text: data[index].h1,
                    		tag: 'font size="3" color="red"',
                    		words: keywordArray
                    	});

                        // build h1 table and set background color
                        if (data[index].h1 === 'H1 Tag Not Used') {
                            htmlH1 += '<tr style="background:#FFA07A; outline: thin solid"><td>' + siteUrls[index] + '</td><td>' + data[index].h1.toString() + '</td></tr>';
                        } else {
                            htmlH1 += '<tr style="background:#98FB98; outline: thin solid"><td>' + siteUrls[index] + '</td><td>' + h1HTML + '</td></tr>';
                        }
                        
                      //if keyword appears in h2, enlarge and turn red
                        var h2HTML = '';
                        h2HTML = wrapInTags({
                    		text: data[index].h2,
                    		tag: 'font size="3" color="red"',
                    		words: keywordArray
                    	});

                        // build h2 table and set background color
                        if (data[index].h2 === 'H2 Tag Not Used') {
                            htmlH2 += '<tr style="background:#FFA07A; outline: thin solid"><td>' + siteUrls[index] + '</td><td>' + data[index].h2.toString() + '</td></tr>';
                        } else {
                            htmlH2 += '<tr style="background:#98FB98; outline: thin solid"><td>' + siteUrls[index] + '</td><td>' + h2HTML + '</td></tr>';
                        }
                        
                      //if keyword appears in h3, enlarge and turn red
                        var h3HTML = '';
                        h3HTML = wrapInTags({
                    		text: data[index].h3,
                    		tag: 'font size="3" color="red"',
                    		words: keywordArray
                    	});

                        // build h3 table and set background color
                        if (data[index].h3 === 'H3 Tag Not Used') {
                            htmlH3 += '<tr style="background:#FFA07A; outline: thin solid"><td>' + siteUrls[index] + '</td><td>' + data[index].h3.toString() + '</td></tr>';
                        } else {
                            htmlH3 += '<tr style="background:#98FB98; outline: thin solid"><td>' + siteUrls[index] + '</td><td>' + h3HTML + '</td></tr>';
                        }
                        
                      //if keyword appears in h3, enlarge and turn red
                        var imgAltHTML = '';
                        imgAltHTML = wrapInTags({
                    		text: data[index].imgAlts,
                    		tag: 'font size="3" color="red"',
                    		words: keywordArray
                    	});
                        
                        htmlImgAlts += '<tr style="outline: thin solid"><td>' + siteUrls[index] + '</td><td>' + imgAltHTML + '</td></tr>';

                        // build word count table
                        htmlWordCount += '<tr style="outline: thin solid"><td>' + siteUrls[index] + '</td><td>' + data[index].keywordCount.toString() + '</td></tr>';

                        // Add up all wordcounts to use for average word count
                        if (data[index].keywordCount !== 'Error Retrieving Page Word Count') {
                            totalWordCount = parseInt(totalWordCount) + parseInt(data[index].keywordCount);
                            i = parseInt(i) + 1;
                        }
                        
//                        htmlPageSpeed += '<tr style="outline: thin solid"><td>' + siteUrls[index] + '</td><td>' + data[index].websiteSpeed.toString() + '</td></tr>';

                    });
                
                totalWordCount = Math.round(parseInt(totalWordCount) / i);
                htmlWordCount += '<tr style="background:#23282d; outline: thin solid"><td style="color:white; font-weight: bold;">Average Word Count' + '</td><td style="color:white; font-weight: bold;">' + totalWordCount.toString() + '</td></tr>';

                // append html to tables
                $('#tblSites tr').first().after(htmlSites);
                $('#tblMetaKeywords tr').first().after(htmlMetaKeyword);
                $('#tblMetaDescription tr').first().after(htmlMetaDescription);
                $('#tblPageTitle tr').first().after(htmlTitle);
                $('#tblPageH1 tr').first().after(htmlH1);
                $('#tblPageH2 tr').first().after(htmlH2);
                $('#tblPageH3 tr').first().after(htmlH3);
                $('#tblImgAlts tr').first().after(htmlImgAlts);
                $('#tblPageWords tr').first().after(htmlWordCount);
                $('#tblPageKWDensity tr').first().after(htmlKeywordDensity);
//                $('#tblPageSpeed tr').first().after(htmlPageSpeed);
                $.unblockUI();
                $('#tables-group').fadeIn("slow");
            }
           
           //wrap output in tags if they contain part of keyword 
           function wrapInTags(opts) {
                var tag = opts.tag || 'strong',
                    words = opts.words || [],
                    regex = RegExp(words.join('|'), 'gi'), // case insensitive
                    replacement = '<' + tag + '>$&</' + tag + '>',
                    text = opts.text;

                    return text.replace(regex, replacement);

            

        }
           
           var clean_text = function(text){
               text = text.replace(/"/g, '""');
               return '"'+text+'"';
           };
           
        //Export to CSV   
        function exportToCSV(tableNames) {
        	
        }
});
