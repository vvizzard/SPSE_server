var _slicedToArray = function () { function sliceIterator(arr, i) { var _arr = []; var _n = true; var _d = false; var _e = undefined; try { for (var _i = arr[Symbol.iterator](), _s; !(_n = (_s = _i.next()).done); _n = true) { _arr.push(_s.value); if (i && _arr.length === i) break; } } catch (err) { _d = true; _e = err; } finally { try { if (!_n && _i["return"]) _i["return"](); } finally { if (_d) throw _e; } } return _arr; } return function (arr, i) { if (Array.isArray(arr)) { return arr; } else if (Symbol.iterator in Object(arr)) { return sliceIterator(arr, i); } else { throw new TypeError("Invalid attempt to destructure non-iterable instance"); } }; }();

function _toConsumableArray(arr) { if (Array.isArray(arr)) { for (var i = 0, arr2 = Array(arr.length); i < arr.length; i++) { arr2[i] = arr[i]; } return arr2; } else { return Array.from(arr); } }

import React, { useState, useEffect } from 'react';
import { NavLink, useParams } from 'react-router-dom';
import { MultiSelect } from "react-multi-select-component";
import CONST from "Constants/general";

export default function Detail(props) {

    var params = useParams();

    var _useState = useState([]),
        _useState2 = _slicedToArray(_useState, 2),
        responses = _useState2[0],
        setResponses = _useState2[1];

    var _useState3 = useState([]),
        _useState4 = _slicedToArray(_useState3, 2),
        responseToShow = _useState4[0],
        setResponseToShow = _useState4[1];

    var _useState5 = useState([]),
        _useState6 = _slicedToArray(_useState5, 2),
        responseHtml = _useState6[0],
        setResponseHtml = _useState6[1];

    var _useState7 = useState(2019),
        _useState8 = _slicedToArray(_useState7, 2),
        annee = _useState8[0],
        setAnnee = _useState8[1];

    var _useState9 = useState([]),
        _useState10 = _slicedToArray(_useState9, 2),
        annees = _useState10[0],
        setAnnees = _useState10[1];

    function loadDate() {
        var years = [];
        var today = parseInt(new Date().getFullYear());
        for (var index = today; index > 2018; index--) {
            years.push(index);
        }
        setAnnees(years);
    }

    function getData() {
        console.log("params");
        console.log(params);
        window.api.getTrans('asynchronous-get-trans', 'reponse', { id: params.id, level: "district", date: annee }).then(function (result) {
            console.log("data response : ___________");
            console.log(result);
            setResponses(result[0]);
            var rts = {};
            responses.map(function (e) {
                if (e.question_mere_id == null) {
                    rts["id_" + e.question] = [];
                    rts["id_" + e.question].push(e);
                } else {
                    rts["id_" + e.question_mere_id].push(e);
                }
            });
            console.log("new response : ");
            console.log(rts);
            setResponseToShow(rts);
            showResp(Object.values(rts));
        });
    }

    function showResp(arr) {
        var valiny = [];
        arr.map(function (e) {

            valiny.push(React.createElement(
                'div',
                null,
                React.createElement(
                    'h3',
                    { className: 'question_princ' },
                    e[0].question_label + " : " + e[0].reponse + e[0].unite
                )
            ));

            var head = [];
            var bodyTd = [];
            var body = [];

            var breakPoint = 0;

            for (var i = 1; i < e.length; i++) {
                if (i > 1 && e[i + 1] && e[i + 1].is_principale == 1) {
                    breakPoint = i;
                }
                head.push(e[i].question_label);
                bodyTd.push(React.createElement(
                    'td',
                    { key: 'qb_' + e[i].question_label + '_' + i },
                    e[i].reponse
                ));
                if (i % breakPoint == 0 || i + 1 == e.length) {
                    body.push(React.createElement(
                        'tr',
                        { key: "rsp_" + e[i].question_label + i },
                        bodyTd.map(function (e) {
                            return e;
                        })
                    ));
                    bodyTd = [];
                }
            }

            valiny.push(React.createElement(
                'table',
                { className: 'table' },
                React.createElement(
                    'thead',
                    null,
                    React.createElement(
                        'tr',
                        null,
                        [].concat(_toConsumableArray(new Set(head))).map(function (emt, i) {
                            return React.createElement(
                                'th',
                                { key: 'qh_' + emt + '_' + i },
                                emt
                            );
                        })
                    )
                ),
                React.createElement(
                    'tbody',
                    null,
                    body.map(function (emt) {
                        return emt;
                    })
                )
            ));
        });
        setResponseHtml(valiny);
    }

    function updateData(date) {
        window.api.getTrans('asynchronous-get-trans', 'reponse', { id: params.id, level: CONST.LEVEL_DISTRICT, date: date }).then(function (result) {
            console.log("data response : ___________");
            console.log(result);
            setResponses(result);
        });
    }

    var handleOnChangeAnnee = function handleOnChangeAnnee(val) {
        setAnnee(val);
        updateData(val);
    };

    useEffect(function () {
        loadDate();
        getData();
    }, [responses.length]);

    return React.createElement(
        'div',
        { className: 'Users' },
        React.createElement(
            'div',
            { className: 'container' },
            React.createElement(
                'div',
                { className: 'head' },
                React.createElement(
                    'h1',
                    null,
                    'Base de Donn\xE9es '
                ),
                React.createElement(
                    'div',
                    { className: 'track' },
                    React.createElement(
                        'ul',
                        null,
                        React.createElement(
                            'li',
                            { className: 'track-item' },
                            React.createElement(
                                NavLink,
                                { to: '/home' },
                                'Accueil'
                            )
                        ),
                        '<',
                        React.createElement(
                            'li',
                            { className: 'track-item' },
                            'Base de donn\xE9es'
                        )
                    )
                )
            ),
            React.createElement(
                'div',
                { className: 'component' },
                React.createElement(
                    'div',
                    { className: 'awaiting' },
                    React.createElement(
                        'div',
                        { className: 'head-filter' },
                        React.createElement(
                            'h2',
                            null,
                            'D\xE9tail'
                        ),
                        React.createElement(
                            'div',
                            { className: 'form-group' },
                            React.createElement(
                                'label',
                                { htmlFor: '' },
                                'Ann\xE9e : '
                            ),
                            React.createElement(
                                'select',
                                { name: '', id: '', value: annee,
                                    onChange: function onChange(event) {
                                        return handleOnChangeAnnee(event.target.value);
                                    } },
                                annees && annees.map(function (e) {
                                    return React.createElement(
                                        'option',
                                        { value: e },
                                        e
                                    );
                                })
                            )
                        )
                    ),
                    React.createElement('br', null),
                    responseHtml && responseHtml.map(function (e) {
                        return e;
                    })
                )
            )
        )
    );
}