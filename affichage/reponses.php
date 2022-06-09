<?php
	require 'dbaccess.php';
	
	/**
   * @name formatResponse
   * @description Format response for front-end use
   * @param {Array} questions
   * @param {Array} reponses
   * @param {JSON} indicateurs
   *
   * @returns {JSON} Formated response
   */
  function formatResponse($questions, $reponses, $indicateurs) {
    // check if there is district in reponses
    if (
      !isset($reponses)  ||
      $reponses.length == 0 ||
      !isset($reponses[0]) ||
      !isset($reponses[0]["_District_"])
    ) {
      return false;
    }

    // change district so it can be in the header of table in front
    $questions[0] = ["label" => " District ", "question" => " District " ];

    // format responses
    $farany = [
		"indicateurs" => $indicateurs,
      	"reponses" => $reponses,
      	"questions" => $questions
	];

    return farany;
  }

  /**
   * @name getIndicateur
   * @description Get indicateurs value by givin the thematique
   * and the reponses in wich calculate the indicateurs
   * @param {*} thematique
   * @param {Array} reponses
   *
   * @returns {JSON} JSON containing calculated indicateurs
   */
  function getIndicateur($thematique, $reponses) {
    try {
      $indicateurs = groupIndicateurWithQuestion($thematique);
      $indicateur = [];
      $indicateurTemporaire = [];
	foreach ($reponses as $resp) {
		foreach ($indicateurs as $ind) {
			
			if (!isset($ind["question_filtre"])) {
				// SUM
				if (
				  indicateurTemporaire[
					ind.label.replaceAll(/[^a-zA-Z0-9]/g, "_")
				  ] &&
				  indicateurTemporaire[ind.label.replaceAll(/[^a-zA-Z0-9]/g, "_")][
					"somme"
				  ]
				) {
				  if (
					resp[ind.question_principale.replaceAll(/[^a-zA-Z0-9]/g, "_")]
				  ) {
					indicateurTemporaire[
					  ind.label.replaceAll(/[^a-zA-Z0-9]/g, "_")
					]["somme"] +=
					  resp[
						ind.question_principale.replaceAll(/[^a-zA-Z0-9]/g, "_")
					  ];
				  }
				} else {
				  let indTemp = {};
				  if (
					resp[ind.question_principale.replaceAll(/[^a-zA-Z0-9]/g, "_")]
				  ) {
					indTemp.somme =
					  resp[
						ind.question_principale.replaceAll(/[^a-zA-Z0-9]/g, "_")
					  ];
				  } else {
					indTemp.somme = 0;
				  }
				  indicateurTemporaire[ind.label.replaceAll(/[^a-zA-Z0-9]/g, "_")] =
					indTemp;
				}
				// COUNT
				if (
				  indicateurTemporaire[ind.label.replaceAll(/[^a-zA-Z0-9]/g, "_")][
					"count"
				  ]
				) {
				  if (
					resp[ind.question_principale.replaceAll(/[^a-zA-Z0-9]/g, "_")]
				  ) {
					indicateurTemporaire[
					  ind.label.replaceAll(/[^a-zA-Z0-9]/g, "_")
					]["count"]++;
				  }
				} else {
				  indicateurTemporaire[ind.label.replaceAll(/[^a-zA-Z0-9]/g, "_")][
					"count"
				  ] = 1;
				}
			  } else {
				if (
				  !indicateurTemporaire[ind.label.replaceAll(/[^a-zA-Z0-9]/g, "_")]
				) {
				  indicateurTemporaire[ind.label.replaceAll(/[^a-zA-Z0-9]/g, "_")] =
					{};
				}
				if (
				  !indicateurTemporaire[ind.label.replaceAll(/[^a-zA-Z0-9]/g, "_")][
					resp[ind.question_filtre.replaceAll(/[^a-zA-Z0-9]/g, "_")]
				  ]
				) {
				  indicateurTemporaire[ind.label.replaceAll(/[^a-zA-Z0-9]/g, "_")][
					resp[ind.question_filtre.replaceAll(/[^a-zA-Z0-9]/g, "_")]
				  ] = {};
				}
				let indicateurTemp = {};
				let first = false;
				if (
				  indicateurTemporaire[ind.label.replaceAll(/[^a-zA-Z0-9]/g, "_")][
					resp[ind.question_filtre.replaceAll(/[^a-zA-Z0-9]/g, "_")]
				  ]["somme"]
				) {
				  if (
					resp[ind.question_principale.replaceAll(/[^a-zA-Z0-9]/g, "_")]
				  ) {
					indicateurTemporaire[
					  ind.label.replaceAll(/[^a-zA-Z0-9]/g, "_")
					][resp[ind.question_filtre.replaceAll(/[^a-zA-Z0-9]/g, "_")]][
					  "somme"
					] +=
					  resp[
						ind.question_principale.replaceAll(/[^a-zA-Z0-9]/g, "_")
					  ];
				  }
				} else {
				  if (
					resp[ind.question_principale.replaceAll(/[^a-zA-Z0-9]/g, "_")]
				  )
					indicateurTemp.somme =
					  resp[
						ind.question_principale.replaceAll(/[^a-zA-Z0-9]/g, "_")
					  ];
				  else indicateurTemp.somme = 0;
				  first = true;
				}
	
				if (
				  indicateurTemporaire[ind.label.replaceAll(/[^a-zA-Z0-9]/g, "_")][
					resp[ind.question_filtre.replaceAll(/[^a-zA-Z0-9]/g, "_")]
				  ]["count"]
				) {
				  indicateurTemporaire[ind.label.replaceAll(/[^a-zA-Z0-9]/g, "_")][
					resp[ind.question_filtre.replaceAll(/[^a-zA-Z0-9]/g, "_")]
				  ]["count"]++;
				} else {
				  indicateurTemp.count = 1;
				  first = true;
				}
	
				if (first) {
				  indicateurTemporaire[ind.label.replaceAll(/[^a-zA-Z0-9]/g, "_")][
					resp[ind.question_filtre.replaceAll(/[^a-zA-Z0-9]/g, "_")]
				  ] = indicateurTemp;
				}
			  }
		}
	}

      

      indicateurs.forEach((ind) => {
        if (indicateurTemporaire[ind.label.replaceAll(/[^a-zA-Z0-9]/g, "_")]) {
          if (ind.sum == 1) {
            if (
              indicateurTemporaire[ind.label.replaceAll(/[^a-zA-Z0-9]/g, "_")][
                "somme"
              ]
            ) {
              indicateur[ind.label] =
                indicateurTemporaire[
                  ind.label.replaceAll(/[^a-zA-Z0-9]/g, "_")
                ]["somme"];
            } else {
              Object.entries(
                indicateurTemporaire[ind.label.replaceAll(/[^a-zA-Z0-9]/g, "_")]
              ).map(([key, value]) => {
                indicateur[ind.label + " ( " + key + " )"] = value["somme"];
              });
            }
          } else if (ind.count == 1) {
            if (
              indicateurTemporaire[ind.label.replaceAll(/[^a-zA-Z0-9]/g, "_")][
                "count"
              ]
            ) {
              indicateur[ind.label] =
                indicateurTemporaire[
                  ind.label.replaceAll(/[^a-zA-Z0-9]/g, "_")
                ]["count"];
            } else {
              Object.entries(
                indicateurTemporaire[ind.label.replaceAll(/[^a-zA-Z0-9]/g, "_")]
              ).map(([key, value]) => {
                indicateur[ind.label + " ( " + key + " )"] = value["count"];
              });
            }
          } else if (ind.moy == 1) {
            if (
              indicateurTemporaire[ind.label.replaceAll(/[^a-zA-Z0-9]/g, "_")][
                "somme"
              ]
            ) {
              indicateur[ind.label] =
                indicateurTemporaire[
                  ind.label.replaceAll(/[^a-zA-Z0-9]/g, "_")
                ]["somme"] /
                indicateurTemporaire[
                  ind.label.replaceAll(/[^a-zA-Z0-9]/g, "_")
                ]["count"];
            } else {
              Object.entries(
                indicateurTemporaire[ind.label.replaceAll(/[^a-zA-Z0-9]/g, "_")]
              ).map(([key, value]) => {
                indicateur[ind.label + " ( " + key + " )"] =
                  value["somme"] / value["count"];
              });
            }
          }
        }
      });
      log.info("getIndicateurs : indicateurs");
      log.info(indicateur);
      log.info("------------------------------");
      return indicateur;
    } catch (error) {
      log.info("GetIndicateur : error");
      throw error;
    }
  }

  /**
   * @name groupIndicateurWithQuestion
   * @description Get array of indicateur with questions and filter
   * @param {int} thematiqueId
   * @returns {Array}
   */
  async groupIndicateurWithQuestion(thematiqueId) {
    try {
      let indicateurs = await this.dao.all(
        `SELECT 
              i.*, 
              q.label as question_principale,
              qq.label as question_filtre
          FROM indicateur i 
              LEFT JOIN question q ON q.id = i.id_question
              LEFT JOIN question qq ON qq.indicateur_id = i.id
          WHERE thematique_id = ?`,
        [thematiqueId]
      );
      return indicateurs;
    } catch (error) {
      log.info("GroupIndicateurWithQuestion : error");
      throw error;
    }
  }

  /**
   * @name populateReponse
   * @description Populate the given reponse object by the data given
   * @param {JSON} reponseTemplate JSON of reponse object
   * @param {Array} data Array of reponses to put in Reponse
   *
   * @returns {JSON} Populated reponse
   */
  populateReponse(reponseTemplate, data) {
    try {
      let reponse = reponseTemplate;

      data.forEach((element) => {
        if (reponse[element.label.replaceAll(/[^a-zA-Z0-9]/g, "_")] == null) {
          reponse[element.label.replaceAll(/[^a-zA-Z0-9]/g, "_")] = [];
        }
        reponse[element.label.replaceAll(/[^a-zA-Z0-9]/g, "_")].push(
          element.label.includes("Fichiers") || element.label.includes("Image")
            ? "https://spse.llanddev.org/upload/" + element.reponse
            : element.reponse
        );
      });

      log.info("populateReponse : first step : first group");
      log.info(reponse);
      log.info("----------------------------------");

      let farany = [];
      var secondKey = Object.keys(reponse)[1]; //fetched the key at second index
      const nbrResp = reponse[secondKey].length;

      for (let i = 0; i < nbrResp; i++) {
        let temp = {};
        temp["_District_"] = data[0].district;
        farany.push(temp);
      }

      for (const [key, value] of Object.entries(reponse)) {
        if (value)
          value.forEach((element, idx) => {
            if (!farany[idx]) {
              farany[idx] = {};
            }
            farany[idx][key] = element;
          });
      }

      log.info("populateReponse : last step : after regrouping");
      log.info(farany);
      log.info("----------------------------------");

      return farany;
    } catch (error) {
      return [];
    }
  }

  /**
   * @name newReponse
   * @description Initialisation of a response object
   * by giving the questions
   * @param {Array} questions Array of questions to put as attribute
   *
   * @returns {JSON} JSON object with questions as attribute
   * and null values
   */
  newReponse(questions) {
    var reponse = {};
    questions.forEach((element) => {
      reponse[element.label.replaceAll(/[^a-zA-Z0-9]/g, "_")] = null;
    });
    return reponse;
  }

  /**
   * @name getReponseByDistrictByRegion
   * @description Get array of reponse/reponse_non_valide by
   * givine the right params
   * @param {import("sqlite3").sqlite3.Database} database
   * @param {JSON} entity JSON in wich we should put
   * region_id or district_id, thid (thematique_id), date (year),
   * comment (validation is in the comment attribute)
   * NB: if not set, thid = 1 and date = 2022
   * @param {String} table reponse or reponse_non_valide
   * @param {Array} questions list of questions if already disponible.
   * If it's not filled, the function will get it by thid
   *
   * @returns {Array} Reponse as array of Q&A
   */
  function getReponseByDistrictByRegion(
    database,
    entity = {},
    table = "reponse_non_valide",
    questions = []
  ) {
    // Default parameters
    let thId = 1;
    let date = "%2022";
    let districtId = null;
    let regionId = null;

    // Parameters and additionals sql if needed
    let entities = [];
    let additionalWhereClauses = "";

    // Custom parameters if there are
    Object.keys(entity).map((key) => {
      if (key == "date") {
        date = "%" + entity[key];
      } else if (key == "thid") {
        thId = entity[key];
      } else if (key == "district_id") {
        districtId = entity[key];
      } else if (key == "region_id") {
        regionId = entity[key];
      } else if (key == "comment") {
        entities.push(entity[key]);
        if (table == "reponse_non_valide") {
          additionalWhereClauses += " AND resp.comment = ? ";
        }
      } else {
        additionalWhereClauses += "AND " + key + " = ? ";
        entities.push(entity[key]);
      }
    });

    // Get question by thématique
    // Get response by thématique using question
    // JOIN question with reponse
    // Filter the result
    let sql =
      `
      SELECT 
        qs.label, 
        resp.*, 
        dist.id as district_id, 
        dist.label as district, 
        region.id as region_id, 
        region.label as region 
      FROM (
        SELECT distinct q.id, q.label
          FROM question q 
            LEFT JOIN  indicateur i ON i.id_question=q.id
          WHERE q.question_mere_id in (
              SELECT q.id FROM question q
              WHERE q.id in (
                SELECT q.question_mere_id
                FROM thematique t
                  LEFT JOIN indicateur i ON t.id = i.thematique_id
                  LEFT JOIN question q ON i.id_question=q.id
                WHERE t.id = ?
              ) OR q.id in (
                SELECT q.id
                FROM thematique t
                  LEFT JOIN indicateur i ON t.id = i.thematique_id
                  LEFT JOIN question q ON i.id_question=q.id
                WHERE t.id = ? AND q.is_principale = 1
              )
          ) UNION SELECT distinct q.id, q.label
              FROM question q 
                LEFT JOIN  indicateur i ON i.id_question=q.id
              WHERE ( 
                q.id in (
                  SELECT q.id FROM question q
                  WHERE q.id in (
                    SELECT q.question_mere_id
                    FROM thematique t
                        LEFT JOIN indicateur i ON t.id = i.thematique_id
                        LEFT JOIN question q ON i.id_question=q.id
                    WHERE t.id = ?
                  ) OR q.id in (
                    SELECT q.id
                    FROM thematique t
                      LEFT JOIN indicateur i ON t.id = i.thematique_id
                      LEFT JOIN question q ON i.id_question=q.id
                    WHERE t.id = ? AND q.is_principale = 1
                  ) 
                )
          ) ORDER BY q.id ASC
      ) AS qs LEFT JOIN (
        SELECT * FROM ` +
      table +
      ` rnv 
        WHERE rnv.question_id in (
          SELECT distinct q.id
          FROM question q 
            LEFT JOIN  indicateur i ON i.id_question=q.id
          WHERE q.question_mere_id in (
              SELECT q.id FROM question q
              WHERE q.id in (
                SELECT q.question_mere_id
                FROM thematique t
                  LEFT JOIN indicateur i ON t.id = i.thematique_id
                  LEFT JOIN question q ON i.id_question=q.id
                WHERE t.id = ?
              ) OR q.id in (
                SELECT q.id
                FROM thematique t
                  LEFT JOIN indicateur i ON t.id = i.thematique_id
                  LEFT JOIN question q ON i.id_question=q.id
                WHERE t.id = ? AND q.is_principale = 1
              )
          ) UNION SELECT distinct q.id
              FROM question q 
                LEFT JOIN  indicateur i ON i.id_question=q.id
              WHERE ( 
                q.id in (
                  SELECT q.id FROM question q
                  WHERE q.id in (
                    SELECT q.question_mere_id
                    FROM thematique t
                        LEFT JOIN indicateur i ON t.id = i.thematique_id
                        LEFT JOIN question q ON i.id_question=q.id
                    WHERE t.id = ?
                  ) OR q.id in (
                    SELECT q.id
                    FROM thematique t
                      LEFT JOIN indicateur i ON t.id = i.thematique_id
                      LEFT JOIN question q ON i.id_question=q.id
                    WHERE t.id = ? AND q.is_principale = 1
                  ) 
                )
          ) ORDER BY q.id ASC
        )
      ) AS resp ON resp.question_id = qs.id 
        LEFT JOIN user u ON u.id = resp.user_id
        LEFT JOIN district dist ON dist.id = u.district_id
        LEFT JOIN region ON region.id = dist.region_id
      WHERE 1 = 1 
    `;
    let values = [thId, thId, thId, thId, thId, thId, thId, thId];

    // Add filter by date
    sql += `AND date like ? `;
    values.push(date);

    // Add district filter
    if (districtId != null) {
      sql += `AND district_id = ?`;
      values.push(districtId);
    }

    // ADD additional filter if needed
    // sql += additionalWhereClauses;
    // values = values.concat(entities);

    // Execute
    const rsp = await this.dao.allDB(sql, values, database);

    log.info("getReponseByDistrictByRegion");
    log.info(sql);
    log.info(values);
    log.info(rsp);
    log.info("-----------------------------");

    if (rsp.length == 0) return [];

    // Make new response by putting all the question
    const questionRepository = new QuestionRepository(this.dao);
    questions.length == 0
      ? (questions =
          await questionRepository.getAllQuestionWihtoutIndicateurByThematique(
            entity.thid
          ))
      : 0;
    questions.unshift({ label: "_District_", reponse: rsp[0].district });
    let reponse = this.newReponse(questions);

    log.info("getReponseByDistrictByRegion : make reponse");
    log.info(questions);
    log.info(reponse);
    log.info("-----------------------------");

    // Populate response
    let final = []; // Store the response here

    if (districtId != null) {
      // filter reponse by district
      let valiny = rsp.filter((item) => item.district_id == districtId);
      final = this.populateReponse(reponse, valiny);
    } else {
      // filter data by region
      let data =
        regionId != null
          ? rsp.filter((item) => item.region_id == regionId)
          : rsp;

      // get all district in the region
      const districts = [...new Set(data.map((item) => item.district_id))];

      // get by districts in the region
      districts.forEach((district) => {
        // reset reponse
        reponse = this.newReponse(questions);

        // filter reponse by district
        let filtered = data.filter((item) => item.district_id == district);
        final = final.concat(this.populateReponse(reponse, filtered));
      });
    }

    log.info("getReponseByDistrictByRegion : populated reponse");
    log.info(final);
    log.info("--------------------------------");

    return final;
  }