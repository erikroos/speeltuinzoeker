ALTER TABLE `speeltuin`
  ADD COLUMN `openingstijden`  text NOT NULL AFTER `avg_rating`,
  ADD COLUMN `vergoeding`  text NOT NULL AFTER `openingstijden`;