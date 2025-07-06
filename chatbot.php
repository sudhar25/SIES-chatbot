<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $faq = json_decode(file_get_contents('faq.json'), true);
  $user_input = strtolower(trim($_POST['message']));
  $best_question = "";
  $best_score = 0;
  $fallback_answer = "";

  foreach ($faq as $question => $answer) {
    $cleaned_question = strtolower($question);

    // Levenshtein distance
    $lev_distance = levenshtein($user_input, $cleaned_question);
    $lev_score = max(0, 100 - $lev_distance * 5); // max score is 100

    // Similarity %
    similar_text($user_input, $cleaned_question, $similarity);

    // Keyword Match (bonus if a word is found in question)
    $keyword_bonus = (strpos($cleaned_question, $user_input) !== false) ? 10 : 0;

    // Weighted combined score
    $combined_score = ($similarity * 0.6) + ($lev_score * 0.3) + $keyword_bonus;

    if ($combined_score > $best_score) {
      $best_score = $combined_score;
      $best_question = $question;
    }

    // Save fallback if keyword matched
    if (strpos($cleaned_question, $user_input) !== false && !$fallback_answer) {
      $fallback_answer = $answer;
    }
  }

  // Return best or fallback
  if ($best_score >= 50) {
    echo $faq[$best_question];
  } else if ($fallback_answer) {
    echo $fallback_answer;
  } else {
    echo "Sorry, I couldn't find an answer to that because it is not properly phrased. Please try rephrasing your question.";
  }
  exit;
}
?>
