var _slicedToArray = function () { function sliceIterator(arr, i) { var _arr = []; var _n = true; var _d = false; var _e = undefined; try { for (var _i = arr[Symbol.iterator](), _s; !(_n = (_s = _i.next()).done); _n = true) { _arr.push(_s.value); if (i && _arr.length === i) break; } } catch (err) { _d = true; _e = err; } finally { try { if (!_n && _i["return"]) _i["return"](); } finally { if (_d) throw _e; } } return _arr; } return function (arr, i) { if (Array.isArray(arr)) { return arr; } else if (Symbol.iterator in Object(arr)) { return sliceIterator(arr, i); } else { throw new TypeError("Invalid attempt to destructure non-iterable instance"); } }; }();

import React, { useState, useEffect } from "react";
import { NavLink, useParams } from "react-router-dom";
import { MultiSelect } from "react-multi-select-component";
import CONST from "Constants/general";
import Table from "../../components/Table";
import CarteM from "../../components/CarteM";

export default function Validation(props) {
  var _useState = useState([]),
      _useState2 = _slicedToArray(_useState, 2),
      thematique = _useState2[0],
      setThematique = _useState2[1];

  var _useState3 = useState(["actif"]),
      _useState4 = _slicedToArray(_useState3, 2),
      tabFocus = _useState4[0],
      setTabFocus = _useState4[1];

  var _useState5 = useState([]),
      _useState6 = _slicedToArray(_useState5, 2),
      column = _useState6[0],
      setColumn = _useState6[1];

  var _useState7 = useState([]),
      _useState8 = _slicedToArray(_useState7, 2),
      responses = _useState8[0],
      setResponses = _useState8[1];

  var _useState9 = useState([]),
      _useState10 = _slicedToArray(_useState9, 2),
      districts = _useState10[0],
      setDistricts = _useState10[1];

  var _useState11 = useState(props.user.district_id),
      _useState12 = _slicedToArray(_useState11, 2),
      distId = _useState12[0],
      setDistId = _useState12[1];

  var _useState13 = useState(1),
      _useState14 = _slicedToArray(_useState13, 2),
      th = _useState14[0],
      setTh = _useState14[1];

  var _useState15 = useState(""),
      _useState16 = _slicedToArray(_useState15, 2),
      indicateurs = _useState16[0],
      setIndicateurs = _useState16[1];

  var _useState17 = useState(new Date().getMonth() + 1 + "-" + new Date().getFullYear()),
      _useState18 = _slicedToArray(_useState17, 2),
      annee = _useState18[0],
      setAnnee = _useState18[1];

  // const params = useParams();
  // const [excel, setExcel] = useState("");
  // const [responseToShow, setResponseToShow] = useState([]);
  // const [responseHtml, setResponseHtml] = useState([]);

  function loadDistrict() {
    window.api.getTrans2("asynchronous-get-district-validation", "district", props.user.district_id).then(function (result) {
      setDistricts(result);
    });
  }

  //   function getData() {
  //     console.log("params");
  //     console.log(params);
  //     window.api
  //       .getTrans("asynchronous-get-trans", "reponse_non_valide", {
  //         id: distId,
  //         level: "district",
  //         date: annee,
  //       })
  //       .then((result) => {
  //         console.log("data response : ___________");
  //         console.log(result);
  //         setResponses(result[0]);
  //         let rts = {};
  //         responses.map((e) => {
  //           if (e.question_mere_id == null) {
  //             rts["id_" + e.question] = [];
  //             rts["id_" + e.question].push(e);
  //           } else {
  //             rts["id_" + e.question_mere_id].push(e);
  //           }
  //         });
  //         console.log("new response : ");
  //         console.log(rts);
  //         setResponseToShow(rts);
  //         showResp(Object.values(rts));
  //       });
  //   }

  //   function showResp(arr) {
  //     let valiny = [];
  //     arr.map((e) => {
  //       if (e[0].comment == 1) {
  //         valiny.push(
  //           <div>
  //             <h3 className="question_princ">
  //               {e[0].question_label + " : " + e[0].reponse + e[0].unite}
  //             </h3>
  //           </div>
  //         );

  //         let head = [];
  //         let bodyTd = [];
  //         let body = [];

  //         let breakPoint = 0;

  //         for (let i = 1; i < e.length; i++) {
  //           if (i > 1 && e[i + 1] && e[i + 1].is_principale == 1) {
  //             breakPoint = i;
  //           }
  //           head.push(e[i].question_label);
  //           bodyTd.push(
  //             <td key={"qb_" + e[i].question_label + "_" + i}>{e[i].reponse}</td>
  //           );
  //           if (i % breakPoint == 0 || i + 1 == e.length) {
  //             body.push(
  //               <tr key={"rsp_" + e[i].question_label + i}>
  //                 {bodyTd.map((e) => {
  //                   return e;
  //                 })}
  //               </tr>
  //             );
  //             bodyTd = [];
  //           }
  //         }

  //         valiny.push(
  //           <table className="table">
  //             <thead>
  //               <tr>
  //                 {[...new Set(head)].map((emt, i) => {
  //                   return <th key={"qh_" + emt + "_" + i}>{emt}</th>;
  //                 })}
  //               </tr>
  //             </thead>
  //             <tbody>
  //               {body.map((emt) => {
  //                 return emt;
  //               })}
  //             </tbody>
  //           </table>
  //         );
  //       }
  //     });
  //     setResponseHtml(valiny);
  //   }

  //   function getData(date) {
  //     window.api
  //       .getTrans("asynchronous-get-trans", "reponse_non_valide", {
  //         id: distId,
  //         level: CONST.LEVEL_DISTRICT,
  //         date: date,
  //       })
  //       .then((result) => {
  //         console.log("data response : ___________");
  //         console.log(result);
  //         setResponses(result);
  //       });
  //   }

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

    // console.log("Make header");
    // console.log("Maque header questions");
    // console.log(questions);
    // console.log("Make header column");
    // console.log([
    //   {
    //     Header: " ",
    //     columns: cl,
    //   },
    // ]);
  }

  function makeIndicateur(indicateurs) {
    setIndicateurs(React.createElement(
      "div",
      { className: "indicateurs-table" },
      React.createElement(
        "table",
        { className: "table" },
        React.createElement(
          "thead",
          null,
          React.createElement(
            "tr",
            null,
            React.createElement(
              "th",
              null,
              "Indicateur"
            ),
            React.createElement(
              "th",
              null,
              "Valeur"
            )
          )
        ),
        React.createElement(
          "tbody",
          null,
          Object.entries(indicateurs).map(function (_ref) {
            var _ref2 = _slicedToArray(_ref, 2),
                key = _ref2[0],
                value = _ref2[1];

            return React.createElement(
              "tr",
              null,
              React.createElement(
                "td",
                { key: key + value },
                key
              ),
              React.createElement(
                "td",
                { key: value + key },
                value
              )
            );
          })
        )
      )
    ));
  }

  function getData() {
    var idTh = arguments.length > 0 && arguments[0] !== undefined ? arguments[0] : th;
    var dist = arguments.length > 1 && arguments[1] !== undefined ? arguments[1] : distId;

    window.api.getTrans("asynchronous-get-trans", "reponse_non_valide", {
      id: dist,
      level: "district",
      date: annee,
      thid: idTh,
      comment: 1
    }).then(function (result) {
      console.log("validation : getData");
      console.log(result);
      makeHeader(result[0].questions);
      makeIndicateur(result[0].indicateurs);
      setResponses(result[0].reponses);
    });
  }

  function getThematique() {
    window.api.get("asynchronous-get", "thematique").then(function (result) {
      setThematique(result);
    });
  }

  function reject() {
    window.api.getTrans("rejeter", "reponse", { district_id: distId }).then(function (result) {
      console.log("rejeter : ___________");
      console.log(result);
      if (result) {
        alert("L'opération a été un succes");
        getData();
      } else {
        alert("Une erreur est survenue lors de l'opération");
      }
    });
  }

  function validate() {
    window.api.getTrans("terminer", "reponse", {
      district_id: distId,
      user_id: props.user.id
    }).then(function (result) {
      console.log("Valider : ___________");
      console.log(result);
      if (result) {
        alert("L'opération a été un succes");
        getData();
      } else {
        alert("Une erreur est survenue lors de l'opération");
      }
    });
  }

  useEffect(function () {
    loadDistrict();
    getThematique();
    getData();
  }, []);

  var handleOnChangeDist = function handleOnChangeDist(val) {
    setDistId(val);
    getData(th, val);
  };

  var handleOnClickTab = function handleOnClickTab(idx, thid) {
    var tbs = [];
    tbs[idx] = ["actif"];
    setTabFocus(tbs);
    setTh(thid);
    getData(thid);
  };

  function handleClickValider() {
    validate();
  }

  function handleOnClickRefuser() {
    reject();
  }

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
          { className: "awaiting" },
          React.createElement(
            "div",
            { className: "head-filter" },
            React.createElement(
              "h2",
              null,
              "D\xE9tail"
            ),
            React.createElement(
              "div",
              { className: "form-group" },
              React.createElement(
                "label",
                { htmlFor: "" },
                "District : "
              ),
              React.createElement(
                "select",
                {
                  name: "",
                  id: "",
                  value: distId,
                  onChange: function onChange(event) {
                    return handleOnChangeDist(event.target.value);
                  } },
                districts && districts.map(function (e) {
                  return React.createElement(
                    "option",
                    { value: e.id },
                    e.label
                  );
                })
              )
            )
          ),
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
          indicateurs,
          React.createElement(Table, { columns: column, data: responses, nowrap: true }),
          React.createElement("br", null),
          React.createElement("br", null),
          React.createElement(CarteM, { regionJson: window.api.getMap })
        ),
        React.createElement(
          "div",
          { className: "bottom-btn" },
          React.createElement(
            "button",
            {
              onClick: function onClick() {
                handleOnClickRefuser();
              } },
            "Refuser"
          ),
          React.createElement(
            "button",
            {
              onClick: function onClick() {
                handleClickValider();
              } },
            "Valider"
          )
        )
      )
    )
  );
}