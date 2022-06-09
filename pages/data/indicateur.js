var _slicedToArray = function () { function sliceIterator(arr, i) { var _arr = []; var _n = true; var _d = false; var _e = undefined; try { for (var _i = arr[Symbol.iterator](), _s; !(_n = (_s = _i.next()).done); _n = true) { _arr.push(_s.value); if (i && _arr.length === i) break; } } catch (err) { _d = true; _e = err; } finally { try { if (!_n && _i["return"]) _i["return"](); } finally { if (_d) throw _e; } } return _arr; } return function (arr, i) { if (Array.isArray(arr)) { return arr; } else if (Symbol.iterator in Object(arr)) { return sliceIterator(arr, i); } else { throw new TypeError("Invalid attempt to destructure non-iterable instance"); } }; }();

import React, { useState, useEffect } from "react";
import { NavLink } from "react-router-dom";
import { MultiSelect } from "react-multi-select-component";
import ROUTES from "Constants/routes";
import CONST from "Constants/general";
import Table from "../../components/Table";

export default function Indicateur(props) {
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

  var _useState7 = useState([]),
      _useState8 = _slicedToArray(_useState7, 2),
      indicateurs = _useState8[0],
      setIndicateurs = _useState8[1];

  var _useState9 = useState([]),
      _useState10 = _slicedToArray(_useState9, 2),
      indicateursToShow = _useState10[0],
      setIndicateursToShow = _useState10[1];

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

  function getThematique() {
    window.api.get("asynchronous-get", "thematique").then(function (result) {
      setThematique(result);
    }).catch(function (error) {
      console.log(error);
    });
  }

  // function makeHeader(questions) {
  //   let cl = [];
  //   questions.forEach((element) => {
  //     cl.push({
  //       Header: element.question,
  //       accessor: element.question.replaceAll(/[^a-zA-Z0-9]/g, "_"),
  //     });
  //   });

  //   setColumn([
  //     {
  //       Header: " ",
  //       columns: cl,
  //     },
  //   ]);
  // }

  function makeIndicateur(indicateurs) {
    var cl = [];

    {
      Object.entries(indicateurs[0]).map(function (_ref) {
        var _ref2 = _slicedToArray(_ref, 2),
            key = _ref2[0],
            value = _ref2[1];

        cl.push({
          Header: key,
          accessor: key
        });
      });
    }

    setColumn([{
      Header: " ",
      columns: cl
    }]);

    setIndicateurs(indicateurs);
    setIndicateursToShow(indicateurs);
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
        if (element.reponses.length > 0) {
          // console.log("---------bofffff-----------xxxxxxxxxxx");
          element.indicateurs = Object.assign({
            District: element.reponses[0]["_District_"]
          }, element.indicateurs);
          resTemp.push(element.indicateurs);
          // console.log("---------bofffff-----------xxxxxxxxxxx");
          // console.log(element);
          // console.log(resTemp);
        }
      });
      // setIndicateurs(resTemp);
      console.log("data response : ___________");
      console.log(resTemp);
      // makeHeader(result[0].questions);
      makeIndicateur(resTemp);
    }).catch(function (error) {
      console.log(error);
    });
  }

  useEffect(function () {
    getThematique();
    loadDate();
    getData();
  }, [indicateurs.length]);

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
          React.createElement(Table, { columns: column, data: indicateursToShow, nowrap: true })
        )
      )
    )
  );
}