<?php
// Include the database configuration
require_once __DIR__ . '/../config/database.php';

/**
 * Get a database connection
 * 
 * @return PDO Database connection
 */
function getDatabase() {
    global $pdo;
    return $pdo;
}

/**
 * Execute a query and return all results
 * 
 * @param string $sql SQL query
 * @param array $params Parameters for prepared statement
 * @return array Results
 */
function dbQuery($sql, $params = []) {
    $stmt = getDB()->prepare($sql);
    $stmt->execute($params);
    return $stmt->fetchAll();
}

/**
 * Execute a query and return a single row
 * 
 * @param string $sql SQL query
 * @param array $params Parameters for prepared statement
 * @return array|false Single row or false if no results
 */
function dbQuerySingle($sql, $params = []) {
    $stmt = getDB()->prepare($sql);
    $stmt->execute($params);
    return $stmt->fetch();
}

/**
 * Execute a query that doesn't return results (INSERT, UPDATE, DELETE)
 * 
 * @param string $sql SQL query
 * @param array $params Parameters for prepared statement
 * @return int Number of affected rows
 */
function dbExecute($sql, $params = []) {
    $stmt = getDB()->prepare($sql);
    $stmt->execute($params);
    return $stmt->rowCount();
}

/**
 * Get the last inserted ID
 * 
 * @return string Last inserted ID
 */
function dbLastInsertId() {
    return getDB()->lastInsertId();
}

/**
 * Begin a transaction
 */
function dbBeginTransaction() {
    getDB()->beginTransaction();
}

/**
 * Commit a transaction
 */
function dbCommit() {
    getDB()->commit();
}

/**
 * Rollback a transaction
 */
function dbRollback() {
    getDB()->rollBack();
}