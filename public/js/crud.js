var CrudModule = (function() {
    var vApi;
    var vPage = 1;
    var vFilter = '';
    var vKeyword = '';

    function setApi(apiUrl) {
        vApi = apiUrl;
    }    

    function setFilter(filter) {
        vFilter = filter;
    }    

    function setKeyword(keyword) {
        vKeyword = keyword;
    }    

    function fRead(page = 1, displayDataFunc) {
        vPage = page;
        $.ajax({
            url: vApi + '?page=' + page + '&filter=' + vFilter + '&keyword=' + vKeyword,
            method: 'GET',
            dataType: 'json',
            success: initDisplayData(displayDataFunc),
            error: function(xhr, status, error) {
                console.error(error);
            }
        });
    }

    function fSearchId(id, callback) {
        $.ajax({
            url: vApi+`?filter={"id":"${id}"}`,
            method: 'GET',
            dataType: 'json',
            success: function(response) {
                callback(response);
            },
            error: function(xhr, status, error) {
                console.error(error);
            }
        });
    }

    function refresh(displayDataFunc) {
        fRead(vPage, displayDataFunc);
    }

    function fDelete(id,callback) {
        if (confirm("apakah anda yakin?")) {
            $.ajax({
                url: vApi + '/' + id,
                method: 'DELETE',
                dataType: 'json',
                success: function(response) {
                    appShowNotification(response.success, [response.message]);
                    if (response.success)
                        callback(response);
                },
                error: function(xhr, status, error) {
                    appShowNotification(false, [error]);
                }
            });
        }
    }

    function fEdit(id, callback) {
        $.ajax({
            type: 'GET',
            url: vApi + '/' + id,
            dataType: 'json',
            success: function(response) {
                if (response.success) {
                    callback(response);
                } else
                    appShowNotification(response.success, [response.message]);
            },
            error: function(xhr, status, error) {
                appShowNotification(false, ['Something went wrong. Please try again later.']);
            }
        });
    }

    function fSaveUpload(setup_ajax, dataForm, callback) {
        $.ajax({
            type: 'post',
            url: setup_ajax.url,
            contentType: false,
            processData: false,            
            data: dataForm,
            dataType: 'json',
            success: function(response) {
                callback(response);
            },
            error: function(xhr, status, error) {
                callback(error);
            }
        });
    }    

    function fSave(setup_ajax, dataForm, callback) {
        $.ajax({
            type: setup_ajax.type,
            url: setup_ajax.url,
            data: dataForm,
            dataType: 'json',
            success: function(response) {
                if (response.success) {
                    callback(response);
                }
                appShowNotification(response.success, [response.message]);
            },
            error: function(xhr, status, error) {
                appShowNotification(false, ['Something went wrong. Please try again later.']);
            }
        });
    }    

    function initDisplayData(displayDataFunc) {
        return function(response) {
            displayDataFunc(response);
            displayPagination(response);
        };
    }

    function displayPagination(response) {
        var currentPage = response.meta.current_page;
        var lastPage = response.meta.last_page;
        var pagination = $('#pagination');
        var paginationInfo = $('#pagination-info');
        pagination.empty();
        paginationInfo.empty();
        if (response.meta.total > 0) {
            for (let i = 1; i <= lastPage; i++) {
                var liClass = (i === currentPage) ? 'page-item active' : 'page-item';
                var linkClass = 'page-link';
                var link = `<li class="${liClass}"><a href="javascript:;" class="${linkClass}" data-page="${i}">${i}</a></li>`;
                pagination.append(link);
            }
            paginationInfo.append(`<div>Data ke <span class="badge bg-secondary">${response.meta.from}</span> dari <span class="badge bg-secondary">${response.meta.total}</span> total data</div>`);
        }
    }

    function resetForm(formFieldMapping, formElmt = '#myForm') {
        $.each(formFieldMapping, function(key, value) {
            var fieldInfo = key;
            if (fieldInfo) {
                var fieldAction = value.action;
                var fieldElmt = $(formElmt).find('[id="' + key + '"]');                
                if (fieldAction === "val") {
                    fieldElmt.val("");
                } else if (fieldAction === "select2") {
                    fieldElmt.val("").trigger("change");
                }
            }
        });
    }    

    function populateEditForm(data, formFieldMapping, formElmt = '#myForm') {
        $.each(data, function(key, value) {
            var fieldInfo = formFieldMapping[key];
            if (fieldInfo) {
                var fieldAction = fieldInfo.action;
                var fieldElmt = $(formElmt).find('[id="' + key + '"]');                
                if (fieldAction === "val") {
                    fieldElmt.val(value);
                } else if (fieldAction === "select2") {
                    fieldElmt.val(value).trigger("change");
                }
            }
        });
    }    
    
    return {
        fRead:fRead,
        fEdit:fEdit,
        fDelete:fDelete,
        fSave:fSave,
        fSaveUpload:fSaveUpload,
        fSearchId:fSearchId,
        setApi:setApi,
        setFilter:setFilter,
        setKeyword:setKeyword,
        refresh:refresh,
        resetForm:resetForm,
        populateEditForm:populateEditForm,
        initDisplayData:initDisplayData,
    };
})();
