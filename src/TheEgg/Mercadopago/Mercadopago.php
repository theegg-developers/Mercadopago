<?php namespace TheEgg\Mercadopago;
class Mercadopago{

  protected $client_id;
  protected $secret;
  protected $rest_client;

  function __construct($client_id, $secret, $options = array()){
    $this->client_id = $client_id;
    $this->secret = $secret;
    $rest_client  = new RestClient();
    $this->sandbox = false; 
  }

  function getAccessToken(){
    $app_values = array(
      'grant_type'    => 'client_credentials',
      'client_id'     => $this->client_id,
      'client_secret' => $this->secret 
    );
    $result = $this->rest_client->post('oauth/token', $this->build_query($app_values), RestClient::MIME_FORM);
    if($result->status == 200){
      $result = $result["response"];
      return $result['access_token'];
    }
    else {
      throw new Exception(print_r($result, true));
    }
  }

  function redirectUrlFor($response){
    $type = $this->sandbox ? 'sandbox_init_point' : 'init_point';
    return $response[$type];
  }

  function setupPurchase($in_cents, $params = array()){
    $preference = array(
        "items" => array(
          "unit_price" => (in_cents / 100.0),
          "quantity" => 1,
          "title" =>       params."fetch(" =>title)       {default."fetch(" =>title)},
          "description" => params."fetch(" =>description) {default."fetch(" =>description)},
          "currency_id" => params."fetch(" =>currency_id) {default."fetch(" =>currency_id)},
          "picture_url" => params.fetch(:picture_url) {default.fetch(:picture_url)}
          )
        }],
        external_reference: params.fetch(:external_reference),
        back_urls: {
          success: params.fetch(:success_url) {default.fetch(:success_url)}
        }
      }
      create_preference(preference).fetch('response')
    
  }

  private function createPreference($preference){

  }

  function getPaymentInfo($id){
    try{
      $access_token = $this->getAccessToken();
    }catch(Exception $e){
      return $e->getMessage();
    }

    $uri_prefix = $this->sandbox ? "/sandbox" : "";
    return $this->rest_client->get($uri_prefix + "/collections/notifications/" + $id + "?access_token=" + $access_token);
  }

    def default
      {
        # picture_url: 'http://google.com/img.jpg',
        # success_url: 'http://google.com/success',
        currency_id: 'ARG'
      }.merge(@default)
    end

    def get_payment_info(id)
      id = String(id)
      begin
        access_token = get_access_token
      rescue => e
        return e.message
      end

      uri_prefix = @sandbox ? "/sandbox" : ""
      @rest_client.get(uri_prefix + "/collections/notifications/" + id + "?access_token=" + access_token)
    end
  private
    def create_preference(preference)
      begin
        access_token = get_access_token
      rescue => e
        return e.message
      end

      @rest_client.post("/checkout/preferences?access_token=" + access_token, preference)
    end

    def build_query(params)
      URI.escape(params.collect { |k, v| "#{k}=#{v}" }.join('&'))
    end
  end
end