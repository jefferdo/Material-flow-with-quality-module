var ghgf = document.getElementById("searchup");
ghgf.addEventListener("keyup", function (e) {
    var term = e.target.value.toLowerCase();
    var books = document.getElementsByClassName('searchCon1');
    Array.from(books).forEach(function (book) {
        var title = book.getAttribute("search-items");
        if (title.toLowerCase().indexOf(term) != -1) {
            book.style.display = 'block';
        } else {
            book.style.display = 'none';
        }
    })
});

var asdd = document.getElementById("searchpr");
asdd.addEventListener("keyup", function (e) {
    var term = e.target.value.toLowerCase();
    var books = document.getElementsByClassName('searchCon2');
    Array.from(books).forEach(function (book) {
        var title = book.getAttribute("search-items");
        if (title.toLowerCase().indexOf(term) != -1) {
            book.style.display = 'block';
        } else {
            book.style.display = 'none';
        }
    })
});

var qweq = document.getElementById("searchco");
qweq.addEventListener("keyup", function (e) {
    var term = e.target.value.toLowerCase();
    var books = document.getElementsByClassName('searchCon3');
    Array.from(books).forEach(function (book) {
        var title = book.getAttribute("search-items");
        if (title.toLowerCase().indexOf(term) != -1) {
            book.style.display = 'block';
        } else {
            book.style.display = 'none';
        }
    })
});

dataload('upcoming');
dataload('inprogress');
dataload('completed');

function dataload(location) {
    if (location == 'upcoming') {
        $('#upcoming').load('../assets/php/kanban_load.php?fn=up');
    } else if (location == 'inprogress') {
        $('#inprogress').load('../assets/php/kanban_load.php?fn=inp');
    } else if (location == 'completed') {
        $('#completed').load('../assets/php/kanban_load.php?fn=com');
    }
}

function timefilter(duration, sect) {
    var books = document.getElementsByClassName(sect);
    Array.from(books).forEach(function (book) {
        var title = book.getAttribute("search-time");
        if ((title == 'today') && (duration == 'today')) {
            book.style.display = 'block';
        } else if (((title == 'today') || (title == 'week')) && (duration == 'week')) {
            book.style.display = 'block';
        } else if (((title == 'today') || (title == 'week') || (title == 'month')) && (duration == 'month')) {
            book.style.display = 'block';
        } else if (duration == 'all') {
            book.style.display = 'block';
        } else {
            book.style.display = 'none';
        }
    });
}