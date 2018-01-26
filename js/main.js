$(document).ready(function()
{
  $("#utm_table").tablesorter({
    widgets: ["zebra"],
    sortList: [[5, 1]]})
    .tablesorterPager({
    // target the pager markup - see the HTML block below
    container: $(".pager"),

    // use this url format "http:/mydatabase.com?page={page}&size={size}"
    ajaxUrl: null,

    // process ajax so that the data object is returned along with the
    // total number of rows; example:
    // {
    //   "data" : [{ "ID": 1, "Name": "Foo", "Last": "Bar" }],
    //   "total_rows" : 100
    // }
    ajaxProcessing: function(ajax) {
        if (ajax && ajax.hasOwnProperty('data')) {
            // return [ "data", "total_rows" ];
            return [ajax.data, ajax.total_rows];
        }
    },

    // output string - default is '{page}/{totalPages}';
    // possible variables:
    // {page}, {totalPages}, {startRow}, {endRow} and {totalRows}
    output: '{startRow} to {endRow} ({totalRows})',

    // apply disabled classname to the pager arrows when the rows at
    // either extreme is visible - default is true
    updateArrows: true,

    // starting page of the pager (zero based index)
    page: 0,

    // Number of visible rows - default is 10
    size: 10,

    // if true, the table will remain the same height no matter how many
    // records are displayed. The space is made up by an empty
    // table row set to a height to compensate; default is false
    fixedHeight: false,

    // remove rows from the table to speed up the sort of large tables.
    // setting this to false, only hides the non-visible rows; needed
    // if you plan to add/remove rows with the pager enabled.
    removeRows: false,

    // css class names of pager arrows
    // next page arrow
    cssNext: '.next',
    // previous page arrow
    cssPrev: '.prev',
    // go to first page arrow
    cssFirst: '.first',
    // go to last page arrow
    cssLast: '.last',
    // select dropdown to allow choosing a page
    cssGoto: '.gotoPage',
    // location of where the "output" is displayed
    cssPageDisplay: '.pagedisplay',
    // dropdown that sets the "size" option
    cssPageSize: '.pagesize',
    // class added to arrows when at the extremes
    // (i.e. prev/first arrows are "disabled" when on the first page)
    // Note there is no period "." in front of this class name
    cssDisabled: 'disabled'
});
});
