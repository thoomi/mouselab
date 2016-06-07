'use strict';

/**
 * @ngdoc service
 * @name mouselabApp.dataService
 * @description
 * # dataService
 * Service in the mouselabApp.
 */
angular.module('mouselabApp')
  .service('dataService', function ($http, $rootScope, configData) {
        var participantDatabaseId       = 0;
        var participantId               = 0;
        var participantGroup            = '';
        var participantCondition        = '';
        var participantIsPreviousParticipant = 0;
        var startTime                   = 0;
        var endTime                     = 0;

        var currentExperimentRound   = 1;
        var lastExperimentDatabaseId = 0;
        
        var availableTrials = [];
        var usedTrials      = [];

        // Helping variables to determine if data is already saved
        // For example if the user hits the back button and answers the questions twice
        var isParticipantSaved    = false;
        var isExperimentSaved     = [false, false, false];
        var isStressQuestionSaved = [false, false, false];
        var isDemographicsSaved   = false;
        var isMaximisingSaved     = false;
        var isResilienceSaved     = false;

        // Define private http request methods
        function saveParticipant(callback) {
            var postData = {
                participantId           : participantId,
                participantPreviously   : participantIsPreviousParticipant,
                participantLocation     : configData.getExperimentLocation(),
                participantCondition    : participantCondition,
                participantGroup        : participantGroup
            };

            $http.post(configData.getBaseUrl() + '/participant/create', postData).
                success(function(data) {
                    participantDatabaseId = data.participantDatabaseId;
                    isParticipantSaved = true;

                    callback();
                }).
                error(function() {
                    callback('Error: http request went wrong.');
                });
        }

        function saveExperiment(optionRank, timeToDecision, optionPosition, callback) {
            var postData = {
                participantDatabaseId : participantDatabaseId,
                condition             : configData.getTask(participantGroup, currentExperimentRound),
                conditionPosition     : currentExperimentRound,
                chosenOptionRank      : optionRank,
                timeToDecision        : timeToDecision,
                chosenOptionPosition  : optionPosition
            };

            $http.post(configData.getBaseUrl() + '/experiment/create', postData).
                success(function(data) {
                    lastExperimentDatabaseId = data.experimentDbId;
                    isExperimentSaved[currentExperimentRound-1] = true;

                    callback();
                }).
                error(function() {
                    callback('Error: http request went wrong.');
                });
        }

        function saveStressQuestions(questionsData, callback) {
            var postData = {
                participantDatabaseId  : participantDatabaseId,
                experimentDatabaseId   : lastExperimentDatabaseId,
                satisfactionAnswers    : questionsData.satisfactionAnswers,
                satisfactionAnswersSum : questionsData.satisfactionAnswersSum,
                stressAnswers          : questionsData.stressAnswers,
                stressAnswersSum       : questionsData.stressAnswersSum,
                decisionByStrategy     : questionsData.decidedByStrategy
            };

            $http.post(configData.getBaseUrl() + '/experiment/save/stressquestions', postData).
                success(function() {
                    isStressQuestionSaved[currentExperimentRound-1] = true;
                    callback();
                }).
                error(function() {
                    callback('Error: http request went wrong.');
                });
        }

        function saveDemographicData(age, gender, graduation, status, apprenticeship, academicDegree, callback) {
            var postData = {
                participantDatabaseId : participantDatabaseId,
                age                   : age,
                gender                : gender,
                graduation            : graduation,
                status                : status,
                apprenticeship        : apprenticeship,
                academicDegree        : academicDegree
            };

            $http.post(configData.getBaseUrl() + '/participant/save/demographics', postData).
                success(function() {
                    isDemographicsSaved = true;
                    callback();
                }).
                error(function() {
                    callback('Error: http request went wrong.');
                });
        }

        function saveMaximisingAnswers(answerValues, sumAnswers, callback) {
            var postData = {
                participantDatabaseId : participantDatabaseId,
                answerValues          : answerValues,
                sumAnswers            : sumAnswers
            };

            $http.post(configData.getBaseUrl() + '/participant/save/maximisinganswers', postData).
                success(function() {
                    isMaximisingSaved = true;
                    callback();
                }).
                error(function() {
                    callback('Error: http request went wrong.');
                });
        }
        
        function saveResilienceAnswers(answerValues, sumAnswers, callback) {
            var postData = {
                participantDatabaseId : participantDatabaseId,
                answerValues          : answerValues,
                sumAnswers            : sumAnswers
            };

            $http.post(configData.getBaseUrl() + '/participant/save/resilienceanswers', postData).
                success(function() {
                    isResilienceSaved = true;
                    callback();
                }).
                error(function() {
                    callback('Error: http request went wrong.');
                });
        }

        function saveUserData(email, participateInOther, comments, callback) {
            var postData = {
                email                 : email,
                participateInOther    : participateInOther,
                comments              : comments,
                location              : configData.getExperimentLocation()
            };

            $http.post(configData.getBaseUrl() + '/user/create', postData).
                success(function() {
                    callback();
                }).
                error(function() {
                    callback('Error: http request went wrong.');
                });
        }

        function saveTrainingData(trainingId, optionRank, timeToDecision, callback) {
          var postData = {
            participantDatabaseId : participantDatabaseId,
            trainingId     : trainingId,
            optionRank     : optionRank,
            timeToDecision : timeToDecision
          };

          $http.post(configData.getBaseUrl() + '/participant/save/training', postData).
            success(function() {
              callback();
            }).
            error(function() {
              callback('Error: http request went wrong.');
            });
        }

        return {

            startTime : function() {
                startTime = new Date().getTime();
            },

            endTime : function() {
                endTime = new Date().getTime();
            },

            isLastRound : function () {
                return currentExperimentRound === configData.getMaxRounds();
            },

            getCurrentRound : function () {
              return currentExperimentRound;
            },
            
            initializeTrials : function() {
              availableTrials = configData.getTrials();
              usedTrials      = [];
            },
            
            getNextTrial : function() {
                var trial = availableTrials.pop();
                usedTrials.push(trial);
                
                return trial;
            },

            startNextRound : function () {
                if (currentExperimentRound >= 1 && currentExperimentRound <= configData.getMaxRounds())
                {
                    currentExperimentRound +=1;
                    
                    return true;
                }
                else
                {
                    return false;
                }
            },

            getCurrentTask : function () {
              return configData.getTask(participantGroup, currentExperimentRound);
            },

            initializeParticipant : function (id, isPreviousParticipant, callback) {
                if (isParticipantSaved)
                {
                    callback();
                    return;
                }

                participantId                    = id;
                participantIsPreviousParticipant = isPreviousParticipant;
                participantGroup     = configData.getRandomGroup();
                participantCondition = configData.getRandomCondition();

                if (typeof callback === 'function')
                {
                    saveParticipant(callback);
                }
            },

            saveExperiment : function(optionRank, timeToDecision, optionPosition, callback) {
                if (isExperimentSaved[currentExperimentRound-1])
                {
                    callback();
                    return;
                }

              saveExperiment(optionRank, timeToDecision, optionPosition, callback);
            },

            saveStressQuestions : function (questionsData, callback) {
                if (isStressQuestionSaved[currentExperimentRound-1])
                {
                    callback();
                    return;
                }

                saveStressQuestions(questionsData, callback);
            },

            saveDemographicData : function (age, gender, graduation, status, apprenticeship, academicDegree, callback) {
                if (isDemographicsSaved)
                {
                    callback();
                    return;
                }

                saveDemographicData(age, gender, graduation, status, apprenticeship, academicDegree, callback);
            },

            saveMaximisingAnswers : function (answerValues, sumAnswers, callback) {
                if (isMaximisingSaved)
                {
                    callback();
                    return;
                }

                saveMaximisingAnswers(answerValues, sumAnswers, callback);
            },
            
            saveResilienceAnswers : function (answerValues, sumAnswers, callback) {
                if (isResilienceSaved)
                {
                    callback();
                    return;
                }

                saveResilienceAnswers(answerValues, sumAnswers, callback);
            },

            saveUserData : function(email, participateInOther, comments, callback) {
                saveUserData(email, participateInOther, comments, callback);
            },

            everythingIsValid : function () {
                if (configData.getExperimentLocation() === 'T')
                {
                    return true;
                }
                else 
                {
                    // if the participant id is set and so on
                    var dataIsFine = true;
    
                    if (participantDatabaseId === 0 || participantId === 0 || participantGroup === '')
                    {
                        dataIsFine = false;
                    }
    
                    return dataIsFine;
                }
            },

            saveTrainingData : function (trainingId, optionRank, timeToDecision, callback) {
              saveTrainingData(trainingId, optionRank, timeToDecision, callback);
            },

            incrementSiteNumber : function() {
              $rootScope.$broadcast('siteChange');
            },

            clearAllData : function() {
                participantDatabaseId       = 0;
                participantId               = 0;
                participantGroup            = '';
                participantCondition        = '';
                startTime                   = 0;
                endTime                     = 0;
                currentExperimentRound      = 1;
                lastExperimentDatabaseId    = 0;
                participantIsPreviousParticipant = 0;
                isParticipantSaved    = false;
                isExperimentSaved     = [false, false, false];
                isStressQuestionSaved = [false, false, false];
                isDemographicsSaved   = false;
                isMaximisingSaved     = false;
                isResilienceSaved     = false;

                $rootScope.$broadcast('resetPageCount');
            }
        };
  });
