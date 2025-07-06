<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $faq = json_decode(file_get_contents('faq.json'), true);
  $user_input = strtolower(trim($_POST['message']));
  $best_question = "";
  $best_score = 0;

  foreach ($faq as $question => $answer) {
    $cleaned_question = strtolower($question);
    similar_text($user_input, $cleaned_question, $similarity);
    $lev_distance = levenshtein($user_input, $cleaned_question);
    $lev_score = max(0, 100 - $lev_distance * 5);
    $combined_score = ($similarity * 0.6) + ($lev_score * 0.4);

    if ($combined_score > $best_score) {
      $best_score = $combined_score;
      $best_question = $question;
    }
  }

  if ($best_score >= 60) {
    echo $faq[$best_question];
  } else {
    echo "Sorry, I couldn't find an answer to that.";
  }
  exit;
}
?>
