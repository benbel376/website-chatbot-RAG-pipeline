<div class="detail-unit details-unit" id="bidlist-details" hidden>
      <div class="blog-details-container">

        <!-- Blog Content Section with Pagination -->
        <section class="blog-details-content">

          <!-- Page 1 -->
          <div class="blog-page active" id="page-1">
            
          <h2 class="project-detail-title">Bidlist Optimization</h2>
          
          <h3 class="project-detail-subtitle">Subtitle: Building a Bidlist Optimization Algorithm for Real-Time Bidding (RTB) on The Trade Desk (TTD)</h3>
          
          <div class="blog-details-banner">

          <img src="./assets/images/projects/bidlist-blog-banner.png" alt="Blog Banner">

        </div>
          

          <h3 class="blog-post-mid-title"> Introduction</h3>
          <p class="plog-post-body">
          In the fast-paced world of digital advertising, real-time bidding (RTB) is a key mechanism that enables advertisers to bid for ad impressions in real time. By optimizing bids to win the most valuable impressions, advertisers can improve engagement, drive conversions, and maximize their return on investment. However, effectively navigating RTB requires an algorithmic approach that can process and respond to large-scale data rapidly and adaptively.
          <br><br>
In this blog, I’ll walk through a project where I built a bidlist optimization algorithm tailored for RTB on The Trade Desk (TTD) platform. We’ll go step-by-step through the code, covering data loading, feature engineering, scoring, and bid adjustment, ultimately creating an optimized bidlist that maximizes engagement within budget constraints. I’ll also discuss how machine learning (ML) models could offer alternative approaches to certain stages of this pipeline.
        </p>


        <h3 class="blog-post-mid-title"> 1. Data Loading and Cleaning</h3>
        <p class="plog-post-body">

</p>
        <p class="plog-post-body">
        The first step in any RTB algorithm is gathering data, as the bidlist will ultimately rely on a well-structured dataset. We start by connecting to Google BigQuery and importing data into a DataFrame. This table includes historical bid and ad performance data, providing a strong foundation for analyzing past trends and optimizing future bids.
        </p>

          <div class="code-display-wrapper" id="custom-code-display">
            <div class="code-display-header">
              <span class="code-display-filename">python</span>
              <span class="code-display-actions">
                <button class="code-display-copy">Copy</button>
              </span>
            </div>
            <div class="code-display-body">
              <div class="code-line-numbers"></div>
              <pre><code id="code-content">
from google.cloud import bigquery
import os

os.environ['GOOGLE_APPLICATION_CREDENTIALS'] = '/home/ben/googlecred.json'
client = bigquery.Client()
query = """
    SELECT *
    FROM `learned-fusion-437423-c0.biddata.biddatatable`
"""
query_job = client.query(query)
df = query_job.to_dataframe()
print("Initial Data:")
display(df.head())

              </code></pre>
            </div>
          </div>

          <p class="plog-post-body">
          This initial loading is followed by data cleaning, where we drop irrelevant columns like ‘advertiser_id’ and ‘campaign_id’. This process removes any fields that aren’t directly beneficial to the bid optimization, ensuring we focus only on essential metrics. Additionally, we handle missing values in critical fields to prevent any disruptions later in the pipeline.
          </p> 
          
          
          <div class="code-display-wrapper" id="custom-code-display">
            <div class="code-display-header">
              <span class="code-display-filename">python</span>
              <span class="code-display-actions">
                <button class="code-display-copy">Copy</button>
              </span>
            </div>
            <div class="code-display-body">
              <div class="code-line-numbers"></div>
              <pre><code id="code-content">
drop_columns = [
    'advertiser_id', 'campaign_id', 'creative_id', 'line_item_id', 'game_key', 
    'region', 'city', 'country', 'standard_engagement', 'standard_click', 
    'standard_video_start', 'standard_video_end', 'standard_rendered_imp_duration_millisecond', 
    'quality_rendered_imp_duration_milliseconds'
]
df = df.drop(columns=drop_columns, errors='ignore')


              </code></pre>
            </div>
          </div>

          <h3 class="blog-post-mid-title"> 2. Feature Engineering for KPI Calculation</h3>
          <p class="plog-post-body">
          Key performance indicators (KPIs) help measure success in RTB, with metrics like engagement rate, click rate, viewable rate, and completion rate being critical. By calculating these rates, we gain insight into ad effectiveness. For instance, engagement rate (engagement/impression) helps assess user interaction per impression, while completion rate (video_end/video_start) indicates how often users view ads fully.
            <br><br>
          Calculating rates rather than raw counts ensures that our model captures the intensity of user interactions, which is more relevant for optimizing bids.
          </p>

          <div class="code-display-wrapper" id="custom-code-display">
            <div class="code-display-header">
              <span class="code-display-filename">python</span>
              <span class="code-display-actions">
                <button class="code-display-copy">Copy</button>
              </span>
            </div>
            <div class="code-display-body">
              <div class="code-line-numbers"></div>
              <pre><code id="code-content">
df['engagement_rate'] = df['engagement'] / (df['impression'] + 1e-10)
df['click_rate'] = df['click'] / (df['impression'] + 1e-10)
df['viewable_rate'] = df['viewable'] / (df['impression'] + 1e-10)
df['completion_rate'] = df['video_end'] / (df['video_start'] + 1e-10)
print("Feature engineering completed. 'engagement_rate' and other rates added.")

              </code></pre>
            </div>
          </div>

          <h3 class="blog-post-mid-title"> 3. Categorical Encoding and Label Mapping</h3>
          <p class="plog-post-body">
          Categorical features are prevalent in ad data, such as ‘device type’ and ‘browser.’ Encoding these features enables the algorithm to process categorical variables efficiently, which is essential in transforming qualitative values into quantitative ones. We use LabelEncoder here, which assigns a unique integer to each category. These mappings are saved to ensure that results can be interpreted or reverse-mapped as needed.
          </p>

          <div class="code-display-wrapper" id="custom-code-display">
            <div class="code-display-header">
              <span class="code-display-filename">python</span>
              <span class="code-display-actions">
                <button class="code-display-copy">Copy</button>
              </span>
            </div>
            <div class="code-display-body">
              <div class="code-line-numbers"></div>
              <pre><code id="code-content">
from sklearn.preprocessing import LabelEncoder
import json

label_encodable_columns = [
    'ad_format', 'environment', 'browser', 'os', 'device_make', 'device_type', 
    'rendering_context', 'matchedfoldposition', 'site'
]

label_mappings = {}
for col in label_encodable_columns:
    encoder = LabelEncoder()
    df[col] = df[col].fillna('Unknown')
    df[col] = encoder.fit_transform(df[col].astype(str))
    label_mappings[col] = {int(v): k for k, v in zip(encoder.classes_, encoder.transform(encoder.classes_))}
with open('label_mappings.json', 'w') as f:
    json.dump(label_mappings, f, indent=4)
print("Label encoding completed and mappings saved.")

              </code></pre>
            </div>
          </div>


          <<h3 class="blog-post-mid-title"> 4. Mutual Information-Based Feature Selection</h3>

          <p class="plog-post-body">
          Mutual Information (MI) measures the dependency between variables. Here, we calculate MI between each feature and the target KPI, engagement_rate, to determine which features are most informative for the algorithm.
          </p>
          </div>

          <!-- Page 2 -->
          <div class="blog-page" id="page-2">
          <p class="plog-post-body">
          Selecting only highly informative features minimizes computational load while retaining meaningful data. This helps our algorithm be efficient and accurate.
          </p>

          <div class="code-display-wrapper" id="custom-code-display">
            <div class="code-display-header">
              <span class="code-display-filename">python</span>
              <span class="code-display-actions">
                <button class="code-display-copy">Copy</button>
              </span>
            </div>
            <div class="code-display-body">
              <div class="code-line-numbers"></div>
              <pre><code id="code-content">
from sklearn.feature_selection import mutual_info_regression
target = 'engagement_rate'
inventory_features = available_label_encodable_columns
mi_scores = mutual_info_regression(df[inventory_features], df[target], discrete_features=True)
mi_df = pd.DataFrame({'Feature': inventory_features, 'MI Score': mi_scores}).sort_values(by='MI Score', ascending=False)
print("Top features selected based on mutual information:")
print(mi_df)

              </code></pre>
            </div>
          </div>


          <h3 class="blog-post-mid-title">5. Aggregation of Data by Selected Features</h3>
          <p class="plog-post-body">
            Grouping data based on high-MI features allows us to compute aggregate KPIs, giving a holistic view of ad performance across feature combinations. This grouping approach is key to creating a more accurate bid list by treating each unique feature combination as a distinct entity. 
            By aggregating metrics like engagement, click, and viewable rates, we generate a robust dataset that reflects the performance of each group, which can be leveraged for more targeted bidding.
          </p>

          <div class="code-display-wrapper" id="custom-code-display">
            <div class="code-display-header">
              <span class="code-display-filename">python</span>
              <span class="code-display-actions">
                <button class="code-display-copy">Copy</button>
              </span>
            </div>
            <div class="code-display-body">
              <div class="code-line-numbers"></div>
              <pre><code id="code-content">
grouped_df = df.groupby(top_features).agg({
    'engagement': 'sum', 
    'click': 'sum', 
    'viewable': 'sum', 
    'video_end': 'sum', 
    'video_start': 'sum', 
    'impression': 'sum'
}).reset_index()

grouped_df['engagement_rate'] = grouped_df['engagement'] / (grouped_df['impression'] + 1e-10)
grouped_df['click_rate'] = grouped_df['click'] / (grouped_df['impression'] + 1e-10)
grouped_df['viewable_rate'] = grouped_df['viewable'] / (grouped_df['impression'] + 1e-10)
grouped_df['completion_rate'] = grouped_df['video_end'] / (grouped_df['video_start'] + 1e-10)
grouped_df['group_size'] = grouped_df['impression']
print("Grouped data with aggregated KPI rates:")
display(grouped_df.head())
              </code></pre>
            </div>
          </div>

          <p class="plog-post-body">
            This code calculates aggregate KPIs across the selected groups, transforming raw engagement counts into calculated rates that represent each group’s performance. 
            For example, the ‘engagement_rate’ metric now provides a clearer indication of user interest within each group. By focusing on these aggregated KPIs, we can further refine our bidding strategy to favor groups with higher potential.
          </p>

          </div>
          <div class="blog-page active" id="page-3">

            <p class="plog-post-body">
            This code calculates aggregate KPIs across the selected groups, transforming raw engagement counts into calculated rates that represent each group’s performance. 
            For example, the ‘engagement_rate’ metric now provides a clearer indication of user interest within each group. By focusing on these aggregated KPIs, we can further refine our bidding strategy to favor groups with higher potential.
          </p>
            <h3 class="blog-post-mid-title">6. Enhanced Scoring System Using Bayesian Smoothing</h3>
            <p class="plog-post-body">
              To achieve a more reliable performance metric for each group, we apply Bayesian smoothing to the calculated KPI rates. Bayesian smoothing adjusts rates based on group size, adding a level of stability to avoid overemphasizing smaller groups that might show high KPI rates due to limited data. 
              This confidence-adjusted score, factoring in the group size and smoothing priors, offers a more balanced perspective on group performance and helps reduce the influence of extreme values.
            </p>

            <div class="code-display-wrapper" id="custom-code-display">
              <div class="code-display-header">
                <span class="code-display-filename">python</span>
                <span class="code-display-actions">
                  <button class="code-display-copy">Copy</button>
                </span>
              </div>
              <div class="code-display-body">
                <div class="code-line-numbers"></div>
                <pre><code id="code-content">
from scipy.stats import beta as beta_dist

def get_improved_scores(rate, group_size, alpha=0.05, beta=1.0):
    prior_alpha = 1
    prior_beta = 1
    smoothed_rate = (rate * group_size + prior_alpha) / (group_size + prior_alpha + prior_beta + 1e-10)
    volume_factor = 1 - np.exp(-alpha * group_size)
    confidence = beta * (group_size / (group_size + 100))
    score = smoothed_rate * volume_factor * confidence
    return score / (np.max(score) + 1e-10)

grouped_df['engagement_score'] = get_improved_scores(grouped_df['engagement_rate'], grouped_df['group_size'])
      </code></pre>
              </div>
            </div>

            <p class="plog-post-body">
              This code calculates an improved score using Bayesian smoothing and a confidence adjustment. With this approach, rates from larger groups have a proportionally greater influence than those from smaller groups, making the score more reliable. 
              This scoring method better reflects the overall engagement level across groups and is a crucial part of the bid adjustment process.
            </p>
            

            <h3 class="blog-post-mid-title">7. Optimization with Budget Constraint</h3>
            <p class="plog-post-body">
              Now that we have a stable scoring system, the next step is to optimize bid adjustments while adhering to a budget constraint. Our objective function maximizes total expected KPI actions by adjusting bid factors. This is achieved through a constrained optimization where total spend remains within budget.
              This ensures efficient budget usage while maximizing engagement, which is vital for successful RTB strategies.
            </p>

            <div class="code-display-wrapper" id="custom-code-display">
              <div class="code-display-header">
                <span class="code-display-filename">python</span>
                <span class="code-display-actions">
                  <button class="code-display-copy">Copy</button>
                </span>
              </div>
              <div class="code-display-body">
                <div class="code-line-numbers"></div>
                <pre><code id="code-content">
from scipy.optimize import minimize

def bid_adjustment(params, scores):
    base_bid_factor, bid_multiplier = params
    bid_factors = base_bid_factor * np.power(scores, bid_multiplier)
    bid_factors = np.clip(bid_factors, min_bid_factor, max_bid_factor)
    return bid_factors

constraints = {'type': 'ineq', 'fun': budget_constraint}
result = minimize(objective, initial_params, args=(grouped_df['score'].values, grouped_df['group_size'].values, base_conversion_rate), method='SLSQP', bounds=bounds, constraints=constraints)
                </code></pre>
              </div>
            </div>

            <p class="plog-post-body">
              Here, we optimize bid adjustments by defining constraints, where the total spend must stay within budget. This setup uses the Sequential Least SQuares Programming (SLSQP) optimizer, an efficient tool for constrained optimization.
              By optimizing these bid adjustments, the algorithm can allocate higher bids to more promising groups while maintaining cost-effectiveness.
            </p>

            <p class="plog-post-body">
              <strong>Suggested Graphic:</strong> Chart illustrating the optimization process, with budget constraints plotted against optimal bid adjustments.
            </p>

            <h3 class="blog-post-mid-title">8. Validation and Final Bid List Generation</h3>
            <p class="plog-post-body">
              With the optimized bid adjustments in place, the final step is validating and generating the bid list. This process involves checking that all bid values fall within the acceptable range and mapping the numerical labels back to their original categorical values for readability.
              The final bid list provides a straightforward format for review and implementation, ensuring that the bidding strategy is consistent and understandable.
            </p>

            <div class="code-display-wrapper" id="custom-code-display">
              <div class="code-display-header">
                <span class="code-display-filename">python</span>
                <span class="code-display-actions">
                  <button class="code-display-copy">Copy</button>
                </span>
              </div>
              <div class="code-display-body">
                <div class="code-line-numbers"></div>
                <pre><code id="code-content">
final_bid_list = grouped_df[top_features + ['adjusted_bid_cpm']]
for col in top_features:
    final_bid_list[col] = final_bid_list[col].astype(int).map(label_mappings[col])
final_bid_list_json = final_bid_list.to_dict(orient='records')
with open('final_bid_list.json', 'w') as f:
    json.dump(final_bid_list_json, f, indent=4)
                </code></pre>
              </div>
            </div>

            <p class="plog-post-body">
              The validation function ensures the integrity of the bid list by confirming that all bid values are within the specified minimum and maximum bid limits. Once validated, the bid list is ready for export, saved as a JSON file for easy access and implementation in RTB platforms.
            </p>

            <p class="plog-post-body">
              <strong>Suggested Graphic:</strong> Sample table of the final bid list with human-readable categorical attributes.
            </p>
            <h3 class="blog-post-mid-title">9. Machine Learning Alternatives for Scoring and Optimization</h3>
            <p class="plog-post-body">
              Traditional methods are powerful, but machine learning (ML) algorithms can bring additional flexibility and precision to RTB scoring and optimization. ML-based approaches to scoring and bid adjustment have the potential to adapt dynamically based on historical data and can be beneficial for real-time applications.
            </p>

            <p class="plog-post-body">
              <strong>Scoring Alternatives:</strong> Machine learning models like Random Forest and Gradient Boosting can be used for scoring by predicting KPI rates directly from ad features. These models capture non-linear relationships that may not be accounted for in a manual scoring system, potentially improving prediction accuracy.
              <br><br>
              <strong>Pros:</strong> Better capture of non-linear relationships; especially suitable for smaller datasets.
              <br><br>
              <strong>Cons:</strong> Requires significant computational resources, which may limit applicability for real-time decisions.
            </p>

            <p class="plog-post-body">
              <strong>Optimization Alternatives:</strong> Reinforcement learning (RL) and Bayesian Optimization represent advanced approaches for bid adjustments, where RL adapts bids dynamically by learning from previous performance data. These approaches can align with long-term strategy and are ideal for real-time optimization, provided there are sufficient resources.
              <br><br>
              <strong>Pros:</strong> Real-time adaptability, better suited for long-term strategies.
              <br><br>
              <strong>Cons:</strong> Complexity in implementation, higher computational cost.
            </p>

           

          </div>
          <div class="blog-page active" id="page-4">

            <div class="blog-details-banner">

              <img src="./assets/images/projects/bidlist-blog-banner.png" alt="Blog Banner">

            </div>
              <h3 class="blog-post-mid-title">Conclusion and Key Takeaways</h3>
              
              <p class="plog-post-body">
                In this project, we developed an end-to-end bidlist optimization algorithm for real-time bidding (RTB) on The Trade Desk (TTD) platform, covering essential stages like data preprocessing, KPI calculations, scoring, and budget-based optimization. Through mutual information-based feature selection, Bayesian smoothing, and constrained bid adjustments, the algorithm provides a robust framework for maximizing engagement and performance within financial constraints.
                <br><br>
                By building this bidlist optimization model, we not only enhance bid efficiency but also ensure that budget utilization is aligned with performance objectives. This approach is crucial for advertisers aiming to gain a competitive advantage in the dynamic RTB ecosystem.
                <br><br>
                For those looking to take the optimization further, machine learning models offer promising alternatives for scoring and bid adjustment. These models can dynamically adapt to changing market conditions, making them valuable for advertisers seeking real-time flexibility and enhanced precision. However, traditional techniques are powerful on their own, especially in scenarios where simplicity and efficiency are paramount.
              </p>

              <p class="plog-post-body">
                <strong>Future Prospects:</strong> Future improvements could involve implementing real-time ML models, experimenting with advanced reinforcement learning techniques, and exploring deeper feature engineering to better capture user behavior. With advancements in both machine learning and data processing, there is immense potential to refine and elevate RTB optimization further.
              </p>

              <p class="plog-post-body">
                Overall, this project demonstrates the impact of a data-driven approach to RTB, enabling advertisers to strategically allocate resources, drive higher engagement, and improve ROI. With a well-designed bid optimization algorithm, digital advertising campaigns can be more targeted, efficient, and ultimately more successful.
              </p>

              <p class="plog-post-body">
                <strong>Suggested Graphic:</strong> Summary flowchart of the complete bid optimization process, illustrating each stage’s contribution to achieving optimized bid performance.
              </p>
            </div>


        </section>

        <!-- Blog Pagination Section -->
        <div class="blog-pagination-container">
          <button class="blog-prev-page" data-blog-prev-page disabled>Previous</button>
          <span class="blog-pagination-info">Page 1 of 3</span>
          <button class="blog-next-page" data-blog-next-page>Next</button>
        </div>

  </div>
</div>