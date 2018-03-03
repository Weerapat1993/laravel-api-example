/**
 * @api {get} /articles GET Article List
 * @apiSampleRequest /api/articles
 * @apiName articleList
 * @apiGroup Article
 */

/**
 * @api {get} /articles/:id GET Article By ID
 * @apiSampleRequest /api/articles/1
 * @apiName articleByID
 * @apiGroup Article
 */

/**
 * @api {post} /articles POST Article Create
 * @apiSampleRequest /api/articles
 * @apiParam {String} title  Title Stroy
 * @apiParam {String} description  Description
 * @apiParam {String} user_id     User ID
 * @apiName articleCreate
 * @apiGroup Article
 */


/**
 * @api {put} /articles PUT Article Update
 * @apiSampleRequest /api/articles
 * @apiParam {Number} id  Article ID
 * @apiParam {String} [title]  Title Stroy
 * @apiParam {String} [description]  Description
 * @apiParam {String} [user_id]     User ID
 * @apiName articleUpdate
 * @apiGroup Article
 */

/**
 * @api {delete} /articles DELETE Article Delete
 * @apiSampleRequest /api/articles
 * @apiParam {Number} id  Article ID
 * @apiName articleDelete
 * @apiGroup Article
 */