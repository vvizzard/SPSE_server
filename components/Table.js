var _slicedToArray = function () { function sliceIterator(arr, i) { var _arr = []; var _n = true; var _d = false; var _e = undefined; try { for (var _i = arr[Symbol.iterator](), _s; !(_n = (_s = _i.next()).done); _n = true) { _arr.push(_s.value); if (i && _arr.length === i) break; } } catch (err) { _d = true; _e = err; } finally { try { if (!_n && _i["return"]) _i["return"](); } finally { if (_d) throw _e; } } return _arr; } return function (arr, i) { if (Array.isArray(arr)) { return arr; } else if (Symbol.iterator in Object(arr)) { return sliceIterator(arr, i); } else { throw new TypeError("Invalid attempt to destructure non-iterable instance"); } }; }();

function _toConsumableArray(arr) { if (Array.isArray(arr)) { for (var i = 0, arr2 = Array(arr.length); i < arr.length; i++) { arr2[i] = arr[i]; } return arr2; } else { return Array.from(arr); } }

import React, { useEffect } from 'react';
import { useAsyncDebounce, useFilters, useGlobalFilter, useSortBy, useTable } from 'react-table';
import { matchSorter } from 'match-sorter';
import regeneratorRuntime from "regenerator-runtime";

export default function Table(_ref) {
    var columns = _ref.columns,
        data = _ref.data,
        searchColumn = _ref.searchColumn,
        nowrap = _ref.nowrap;


    // Define a default UI for filtering
    function DefaultColumnFilter(_ref2) {
        var _ref2$column = _ref2.column,
            filterValue = _ref2$column.filterValue,
            preFilteredRows = _ref2$column.preFilteredRows,
            setFilter = _ref2$column.setFilter;

        var count = preFilteredRows.length;

        return React.createElement('input', {
            value: filterValue || '',
            onChange: function onChange(e) {
                setFilter(e.target.value || undefined); // Set undefined to remove the filter entirely
            },
            placeholder: 'Search ' + count + ' records...'
        });
    }

    var defaultColumn = React.useMemo(function () {
        return {
            // Let's set up our default Filter UI
            Filter: DefaultColumnFilter
        };
    }, []);

    function fuzzyTextFilterFn(rows, id, filterValue) {
        return matchSorter(rows, filterValue, { keys: [function (row) {
                return row.values[id];
            }] });
    }

    // Let the table remove the filter if the string is empty
    fuzzyTextFilterFn.autoRemove = function (val) {
        return !val;
    };

    var filterTypes = React.useMemo(function () {
        return {
            // Add a new fuzzyTextFilterFn filter type.
            fuzzyText: fuzzyTextFilterFn,
            // Or, override the default text filter to use
            // "startWith"
            text: function text(rows, id, filterValue) {
                return rows.filter(function (row) {
                    var rowValue = row.values[id];
                    return rowValue !== undefined ? String(rowValue).toLowerCase().startsWith(String(filterValue).toLowerCase()) : true;
                });
            }
        };
    }, []);

    // Define a default UI for filtering
    function GlobalFilter(_ref3) {
        var preGlobalFilteredRows = _ref3.preGlobalFilteredRows,
            globalFilter = _ref3.globalFilter,
            setGlobalFilter = _ref3.setGlobalFilter;

        var count = preGlobalFilteredRows.length;

        var _React$useState = React.useState(globalFilter),
            _React$useState2 = _slicedToArray(_React$useState, 2),
            value = _React$useState2[0],
            setValue = _React$useState2[1];

        var _onChange = useAsyncDebounce(function (value) {
            setGlobalFilter(value || undefined);
        }, 200);

        return React.createElement(
            'div',
            { className: 'table-search' },
            React.createElement(
                'h3',
                null,
                'Rechercher'
            ),
            React.createElement('input', {
                autoFocus: true,
                type: 'text',
                value: value || "",
                onChange: function onChange(e) {
                    setValue(e.target.value);
                    _onChange(e.target.value);
                },
                placeholder: count + ' donn\xE9es \xE0 filtrer...',
                style: {
                    fontSize: '.75rem',
                    padding: '.5em',
                    margin: '.5em 0',
                    border: 'whitesmoke solid 1px',
                    color: '#4a4a4a'
                }
            })
        );
    }

    // This is a custom filter UI for selecting
    // a unique option from a list
    function SelectColumnFilter(_ref4) {
        var _ref4$column = _ref4.column,
            filterValue = _ref4$column.filterValue,
            setFilter = _ref4$column.setFilter,
            preFilteredRows = _ref4$column.preFilteredRows,
            id = _ref4$column.id;

        // Calculate the options for filtering
        // using the preFilteredRows
        var options = React.useMemo(function () {
            var options = new Set();
            preFilteredRows.forEach(function (row) {
                options.add(row.values[id]);
            });
            return [].concat(_toConsumableArray(options.values()));
        }, [id, preFilteredRows]);

        // Render a multi-select box
        return React.createElement(
            'select',
            {
                value: filterValue,
                onChange: function onChange(e) {
                    setFilter(e.target.value || undefined);
                }
            },
            React.createElement(
                'option',
                { value: '' },
                'All'
            ),
            options.map(function (option, i) {
                return React.createElement(
                    'option',
                    { key: i, value: option },
                    option
                );
            })
        );
    }

    var _useTable = useTable({
        columns: columns,
        data: data,
        defaultColumn: defaultColumn,
        filterTypes: filterTypes
    }, useFilters, useGlobalFilter, useSortBy),
        getTableProps = _useTable.getTableProps,
        getTableBodyProps = _useTable.getTableBodyProps,
        headerGroups = _useTable.headerGroups,
        rows = _useTable.rows,
        prepareRow = _useTable.prepareRow,
        state = _useTable.state,
        visibleColumns = _useTable.visibleColumns,
        preGlobalFilteredRows = _useTable.preGlobalFilteredRows,
        setGlobalFilter = _useTable.setGlobalFilter;

    return React.createElement(
        'div',
        null,
        React.createElement(GlobalFilter, {
            preGlobalFilteredRows: preGlobalFilteredRows,
            globalFilter: state.globalFilter,
            setGlobalFilter: setGlobalFilter
        }),
        searchColumn && searchColumn == true ? React.createElement(
            'div',
            { className: 'filter-global' },
            headerGroups.map(function (headerGroup) {
                return headerGroup.headers.map(function (column) {
                    return column.render('Header') != ' ' ? React.createElement(
                        'div',
                        null,
                        column.canFilter ? column.render('Header') : null,
                        React.createElement(
                            'div',
                            null,
                            column.canFilter ? column.render('Filter') : null
                        )
                    ) : '';
                });
            })
        ) : '',
        React.createElement('br', null),
        React.createElement(
            'div',
            { className: 'scrollable-table' },
            React.createElement(
                'table',
                Object.assign({ className: "table " + nowrap ? "no-wrap" : "" }, getTableProps()),
                React.createElement(
                    'thead',
                    null,
                    headerGroups.map(function (headerGroup) {
                        return React.createElement(
                            'tr',
                            headerGroup.getHeaderGroupProps(),
                            headerGroup.headers.map(function (column) {
                                return React.createElement(
                                    'th',
                                    column.getHeaderProps(column.getSortByToggleProps()),
                                    column.render('Header')
                                );
                            })
                        );
                    })
                ),
                React.createElement(
                    'tbody',
                    getTableBodyProps(),
                    rows.map(function (row, i) {
                        prepareRow(row);
                        return React.createElement(
                            'tr',
                            row.getRowProps(),
                            row.cells.map(function (cell) {
                                return React.createElement(
                                    'td',
                                    cell.getCellProps(),
                                    cell.render('Cell')
                                );
                            })
                        );
                    })
                )
            )
        )
    );
}