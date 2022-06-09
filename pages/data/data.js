var _slicedToArray = function () { function sliceIterator(arr, i) { var _arr = []; var _n = true; var _d = false; var _e = undefined; try { for (var _i = arr[Symbol.iterator](), _s; !(_n = (_s = _i.next()).done); _n = true) { _arr.push(_s.value); if (i && _arr.length === i) break; } } catch (err) { _d = true; _e = err; } finally { try { if (!_n && _i["return"]) _i["return"](); } finally { if (_d) throw _e; } } return _arr; } return function (arr, i) { if (Array.isArray(arr)) { return arr; } else if (Symbol.iterator in Object(arr)) { return sliceIterator(arr, i); } else { throw new TypeError("Invalid attempt to destructure non-iterable instance"); } }; }();

import React, { useState, useEffect } from "react";
import { NavLink } from "react-router-dom";
import { MultiSelect } from "react-multi-select-component";
import ROUTES from "Constants/routes";
import CONST from "Constants/general";
import Table from "../../components/Table";

export default function Data(props) {
  // const animatedComponents = makeAnimated();

  var _useState = useState([]),
      _useState2 = _slicedToArray(_useState, 2),
      thematique = _useState2[0],
      setThematique = _useState2[1];

  var _useState3 = useState(["actif"]),
      _useState4 = _slicedToArray(_useState3, 2),
      tabFocus = _useState4[0],
      setTabFocus = _useState4[1];

  var _useState5 = useState(1),
      _useState6 = _slicedToArray(_useState5, 2),
      th = _useState6[0],
      setTh = _useState6[1];

  // const [questions, setQuestions] = useState([]);
  // const [headerTotal, setHeaderTotal] = useState([]);


  var _useState7 = useState([]),
      _useState8 = _slicedToArray(_useState7, 2),
      header = _useState8[0],
      setHeader = _useState8[1];

  var _useState9 = useState([]),
      _useState10 = _slicedToArray(_useState9, 2),
      responses = _useState10[0],
      setResponses = _useState10[1];

  var _useState11 = useState([]),
      _useState12 = _slicedToArray(_useState11, 2),
      annees = _useState12[0],
      setAnnees = _useState12[1];

  var _useState13 = useState([{
    Header: " ",
    columns: [{
      Header: "ID",
      accessor: "id"
    }, {
      Header: "Nom",
      accessor: "label"
    }, {
      Header: "Description",
      accessor: "comment"
    }]
  }]),
      _useState14 = _slicedToArray(_useState13, 2),
      column = _useState14[0],
      setColumn = _useState14[1];

  var _useState15 = useState([{ id: 1 }]),
      _useState16 = _slicedToArray(_useState15, 2),
      data = _useState16[0],
      setData = _useState16[1];

  var _useState17 = useState(parseInt(new Date().getFullYear())),
      _useState18 = _slicedToArray(_useState17, 2),
      annee = _useState18[0],
      setAnnee = _useState18[1];

  var _useState19 = useState(CONST.LEVEL_DISTRICT),
      _useState20 = _slicedToArray(_useState19, 2),
      niveau = _useState20[0],
      setNiveau = _useState20[1];

  function loadDate() {
    var years = [];
    var today = parseInt(new Date().getFullYear());
    for (var index = today; index > 2018; index--) {
      years.push(index);
    }
    setAnnees(years);
  }

  // function getHeader() {
  //   window.api.get("asynchronous-get", "question").then((result) => {
  //     setQuestions(result);
  //     let option = [];
  //     result.forEach((element, idx) => {
  //       // option.push(<Option key={element.id + element.label + element.id} >{element.label}</Option>)
  //       option.push({ label: element.label, value: element.id, index: idx });
  //     });
  //     setHeaderTotal(option);
  //     setHeader(option);
  //   });
  // }

  // function updateData(level, date, thid = th) {
  //   window.api
  //     .getTrans("asynchronous-get-trans", "reponse", {
  //       level: level,
  //       date: date,
  //       thid: thid
  //     })
  //     .then((result) => {
  //       console.log("data response : ___________");
  //       console.log(result);
  //       // setResponses(result);
  //       // makeHeader(header, result);
  //       setResponses(result[0].reponses);
  //       makeHeader(result[0].questions);
  //     });
  // }

  //   function makeHeader(hd = header, rsp = responses) {
  //     var cl = [
  //       {
  //         Header: niveau,
  //         accessor: "acc0",
  //         Cell: (e) => {
  //           let splited = e && e.value ? e.value.split("_") : " _ ";
  //           return niveau === CONST.LEVEL_DISTRICT ? (
  //             <NavLink to={ROUTES.DATA_DETAIL + "/" + splited[1]}>
  //               {splited[0]}
  //             </NavLink>
  //           ) : (
  //             e.value
  //           );
  //         },
  //       },
  //     ];
  //     let lim = 1;
  //     {
  //       hd &&
  //         questions &&
  //         hd.map((e) => {
  //           cl.push({
  //             Header:
  //               questions[e.index].label + "( " + questions[e.index].unite + ")",
  //             accessor: "acc" + lim,
  //           });
  //           lim++;
  //         });
  //     }
  //     const columns = [
  //       {
  //         Header: " ",
  //         columns: cl,
  //       },
  //     ];
  //     console.log("changement de colonne");
  //     console.log(columns);
  //     setColumn(columns);

  //     // Data
  //     var dt = [];
  //     let pas = 0;
  //     {
  //       rsp &&
  //         rsp.map((e, i) => {
  //           let vari = {};
  //           vari["acc0"] = e[0].district + "_" + e[0].district_id;
  //           pas++;
  //           {
  //             hd &&
  //               hd.map((q, j) => {
  //                 vari["acc" + pas] = e[q.index] ? e[q.index].reponse : 0;
  //                 pas++;
  //               });
  //           }
  //           pas = 0;
  //           dt.push(vari);
  //         });
  //     }

  //     {
  //       responses &&
  //         responses.map((e, i) => {
  //           let link =
  //             niveau === CONST.LEVEL_DISTRICT ? (
  //               <NavLink to={ROUTES.DATA_DETAIL + "/" + e[0].district_id}>
  //                 {e[0].district}
  //               </NavLink>
  //             ) : (
  //               e[0].district
  //             );
  //           return (
  //             <tr key={"body_" + "tr" + "_" + i}>
  //               <td>{link}</td>
  //               {header &&
  //                 header.map((q, j) => {
  //                   return (
  //                     <td
  //                       key={
  //                         "body" +
  //                         (e[q.index] ? e[q.index].id : "ss") +
  //                         i +
  //                         "td" +
  //                         "_" +
  //                         j
  //                       }>
  //                       {e[q.index] ? e[q.index].reponse : 0}
  //                     </td>
  //                   );
  //                 })}
  //             </tr>
  //           );
  //         });
  //     }

  //     console.log("changement de données");
  //     console.log(columns);
  //     console.log(dt);
  //     setData(dt);
  //   }

  function getThematique() {
    window.api.get("asynchronous-get", "thematique").then(function (result) {
      setThematique(result);
    }).catch(function (error) {
      console.log(error);
    });
  }

  function makeHeader(questions) {
    var cl = [];
    questions.forEach(function (element) {
      cl.push({
        Header: element.question,
        accessor: element.question.replaceAll(/[^a-zA-Z0-9]/g, "_")
      });
    });

    setColumn([{
      Header: " ",
      columns: cl
    }]);
  }

  function getData() {
    var level = arguments.length > 0 && arguments[0] !== undefined ? arguments[0] : niveau;
    var date = arguments.length > 1 && arguments[1] !== undefined ? arguments[1] : annee;
    var thid = arguments.length > 2 && arguments[2] !== undefined ? arguments[2] : th;

    window.api.getTrans("asynchronous-get-trans", "reponse", {
      level: level,
      date: date,
      thid: thid
    }).then(function (result) {
      console.log("data response : ___________");
      console.log(result);
      var resTemp = [];
      result.forEach(function (element) {
        resTemp = resTemp.concat(element.reponses);
      });
      setResponses(resTemp);
      console.log("data response : ___________");
      console.log(resTemp);
      makeHeader(result[0].questions);
    }).catch(function (error) {
      console.log(error);
    });
  }

  useEffect(function () {
    getThematique();
    loadDate();
    // getHeader();
    getData();
  }, [responses.length]);

  // const handleOnChangeHeader = (v) => {
  //   setHeader(v);
  //   makeHeader(v);
  // };

  var handleOnChangeNiveau = function handleOnChangeNiveau(val) {
    setNiveau(val);
    getData(val);
  };

  var handleOnChangeAnnee = function handleOnChangeAnnee(val) {
    setAnnee(val);
    getData(niveau, val);
  };

  var handleOnClickTab = function handleOnClickTab(idx, thid) {
    var tbs = [];
    tbs[idx] = ["actif"];
    setTabFocus(tbs);
    setTh(thid);
    getData(niveau, annee, thid);
  };

  return React.createElement(
    "div",
    { className: "Users" },
    React.createElement(
      "div",
      { className: "container" },
      React.createElement(
        "div",
        { className: "head" },
        React.createElement(
          "h1",
          null,
          "Base de Donn\xE9es "
        ),
        React.createElement(
          "div",
          { className: "track" },
          React.createElement(
            "ul",
            null,
            React.createElement(
              "li",
              { className: "track-item" },
              React.createElement(
                NavLink,
                { to: "/home" },
                "Accueil"
              )
            ),
            "<",
            React.createElement(
              "li",
              { className: "track-item" },
              "Base de donn\xE9es"
            )
          )
        )
      ),
      React.createElement(
        "div",
        { className: "component" },
        React.createElement(
          "div",
          { className: "filter" },
          React.createElement(
            "div",
            { className: "form-group" },
            React.createElement(
              "label",
              { htmlFor: "" },
              "Niveau"
            ),
            React.createElement(
              "select",
              {
                value: niveau,
                onChange: function onChange(event) {
                  return handleOnChangeNiveau(event.target.value);
                } },
              React.createElement(
                "option",
                { value: CONST.LEVEL_REGION },
                "R\xE9gion"
              ),
              React.createElement(
                "option",
                { value: CONST.LEVEL_DISTRICT },
                "District"
              )
            )
          ),
          React.createElement(
            "div",
            { className: "form-group" },
            React.createElement(
              "label",
              { htmlFor: "" },
              "Ann\xE9e"
            ),
            React.createElement(
              "select",
              {
                name: "",
                id: "",
                value: annee,
                onChange: function onChange(event) {
                  return handleOnChangeAnnee(event.target.value);
                } },
              annees && annees.map(function (e) {
                return React.createElement(
                  "option",
                  { value: e },
                  e
                );
              })
            )
          )
        ),
        React.createElement("br", null),
        React.createElement(
          "div",
          { className: "awaiting" },
          React.createElement(
            "h2",
            null,
            "Tableau des donn\xE9es"
          ),
          React.createElement("br", null),
          React.createElement(
            "div",
            { className: "tab" },
            thematique && thematique.map(function (user, idx) {
              return React.createElement(
                "a",
                {
                  className: tabFocus && tabFocus[idx] ? "item " + tabFocus[idx] : "item",
                  onClick: function onClick() {
                    handleOnClickTab(idx, user.id);
                  } },
                user.label
              );
            })
          ),
          React.createElement("br", null),
          React.createElement(Table, { columns: column, data: responses, nowrap: true })
        )
      )
    )
  );
}